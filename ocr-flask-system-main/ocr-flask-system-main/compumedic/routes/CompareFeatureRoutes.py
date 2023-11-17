from flask import Blueprint, render_template, jsonify, abort, request
import traceback
from compumedic.services.ProductDataScrapperService import ProductScrapped
from compumedic.utils.Logger import Logger
from compumedic.cache import cache
from compumedic.services.CompareFeatureService import Scraper

from cleantext import clean
import urllib.parse
import re

compare = Blueprint('compare_feature_blueprint',
                    __name__, static_folder='../static')


def is_valid_query(query):
    return query is not None and query.strip() != ''


@compare.route('/', methods=['GET'])
def compara():
    query = request.args.get('query', '')
    query = re.split(" &#8211; | - | – ", query)[0]
    query = clean(query)
    query = urllib.parse.unquote(query)
    if not is_valid_query(query):
        abort(404)
    clave_cache = f'-response-{query}'
    list_stores = cache.get(clave_cache)
    if list_stores is None:
        try:
            product_service = Scraper(query)
            list_stores = product_service.get_data()
            cache.set(clave_cache, list_stores)
        except IndexError:
            cache.delete(clave_cache)
            return jsonify({'error': "El medicamento no se encuentra en nuestra base de datos."}), 500
        except Exception as e:
            cache.delete(clave_cache)
            Logger.add_to_log("error", str(e))
            Logger.add_to_log("error", traceback.format_exc())
            return jsonify({'error': str(e)}), 500
    if request.is_json:
        return jsonify(list_stores)
    else:
        return render_template('compare_product.jinja2', stores=list_stores)

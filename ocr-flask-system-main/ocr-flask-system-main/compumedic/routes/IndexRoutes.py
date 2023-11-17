from flask import Blueprint, jsonify, request, render_template

import traceback

from compumedic.utils.Logger import Logger

# Logger

main = Blueprint('index_blueprint', __name__)


@main.get('/')
def index():
    try:
        Logger.add_to_log("info", "{} {}".format(request.method, request.path))
        return render_template('upload_recipe.jinja2', titulo="OCR Flask")
    except Exception as ex:
        raise ex
        Logger.add_to_log("error", str(ex))
        Logger.add_to_log("error", traceback.format_exc())
        response = jsonify({'message': "Internal Server Error", 'success': False})
        return response


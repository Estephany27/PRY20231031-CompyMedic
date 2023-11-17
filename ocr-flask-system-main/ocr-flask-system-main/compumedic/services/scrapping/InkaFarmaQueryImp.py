from urllib.parse import urlencode

import requests

from compumedic.services.ProductDataScrapperService import IScrapperQuery, ProductScrapped


def get_data_product_algolia(query):
    params = {
        "x-algolia-agent": "Algolia for JavaScript (4.13.1); Browser",
        "x-algolia-api-key": "eb3261874e9b933efab019b04acff834",
        "x-algolia-application-id": "15W622LAQ4",
    }
    search_url = "https://15w622laq4-dsn.algolia.net/1/indexes/products/query?" + urlencode(params)
    search_data = {
        "query": query
    }

    response = requests.post(search_url, json=search_data)
    return response.json().get('hits')


def search_product_data_inkafarma(query):
    objectID = get_data_product_algolia(query)[0].get('objectID')
    return get_data_product_aws(objectID)


def get_data_product_aws(objectID):
    query = "companyCode=IKF&saleChannel=WEB&saleChannelType=DIGITAL&sourceDevice=null"
    api_url = f"https://5doa19p9r7.execute-api.us-east-1.amazonaws.com/MMPROD/product/{objectID}?{query}"
    return requests.get(api_url).json()


class InkaFarmaQueryImp(IScrapperQuery):
    STORE_NAME = "InkaFarma"
    STORE_LOGO = "Inkafarma.png"
    STORE_ID = 5
    
    url = None
    query = None

    def __init__(self, query):
        super().__init__(query)
        self.query = query

    def execute_query(self):
        print("Quering Farmacia Universal")
        return search_product_data_inkafarma(self.query)

    def get_result(self) -> ProductScrapped:
        data = self.execute_query()
        return ProductScrapped(name=data.get('name'), price=data.get('price'),
                               store_id=self.STORE_ID, photo=data.get('imageList')[0].get('url'))

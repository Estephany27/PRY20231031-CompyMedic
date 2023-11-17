
import requests
from flask import abort
from compumedic.services.ProductDataScrapperService import IScrapperQuery, ProductScrapped


class BoticasYSaludQueryImp(IScrapperQuery):
    STORE_NAME = "BoticasHogarYSalud"
    STORE_LOGO = "BoticasHogarYSalud.png"
    STORE_ID = 3
    url = None
    query = None
    def __init__(self, query):
        super().__init__(query)
        self.query = query
    def execute_query(self):
        print("Boticas Y Salud QUERY")
        url = "https://bys-prod-backend.azurewebsites.net/api/ServiceProduct/searchbyfilter"
        querystring = {
            "filter": self.query
        }
        payload = ""
        headers = {
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) "
                          "Chrome/116.0.0.0 Safari/537.36"
        }

        response = requests.request("GET", url, data=payload, headers=headers, params=querystring)
        if len(response.json().get('data')) == 0:
            abort(404, description="No se encontro el producto")
        return response.json().get('data')[0].get('filters')[0]
    def get_result(self) -> ProductScrapped:
        data = self.execute_query()
        return ProductScrapped(name=data.get('title').strip(), price=data.get('price'),
                               store_id=self.STORE_ID, photo=data.get('image'))

from flask import abort
import requests
from bs4 import BeautifulSoup

from compumedic.services.ProductDataScrapperService import IScrapperQuery, ProductScrapped


class FarmaciaUniversalQueryImp(IScrapperQuery):
    STORE_NAME = "FarmaciaUniversal"
    STORE_LOGO = "FarmaciaUniversal.png"
    STORE_ID = 5
    url = None
    query = None

    def __init__(self, query):
        super().__init__(query)
        self.query = query

    def execute_query(self):
        import requests
        url = "https://farmaciauniversal.com/buscador"
        querystring = {"term": self.query}

        payload = ""
        headers = {
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) "
                          "Chrome/116.0.0.0 Safari/537.36"}
        response = requests.request("GET", url, data=payload, headers=headers, params=querystring)
        
        if len(response.json()) ==0 :
            abort(404, description="No se encontro el producto")
        self.url = "http://farmaciauniversal.com/" + response.json()[0].get('url')
        return response.json()[0]

    def get_result(self) -> ProductScrapped:
        data = self.execute_query()
        html_content = requests.get(self.url).content
        soup = BeautifulSoup(html_content, 'html.parser')
        title = soup.find("meta", property="og:title")
        price = soup.findAll('p', {'class': 'texto azul precio izquierda talla25 em4'})[0].get_text().replace('S/', '')
        img = "https://farmaciauniversal.com/"+data.get('imagen')
        return ProductScrapped(name=title["content"], price=price, store_id=self.STORE_ID, photo=img)

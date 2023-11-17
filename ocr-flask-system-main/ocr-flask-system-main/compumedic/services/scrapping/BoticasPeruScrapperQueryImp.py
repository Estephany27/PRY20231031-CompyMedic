import requests
from bs4 import BeautifulSoup

from compumedic.services.ProductDataScrapperService import IScrapperQuery, ProductScrapped


class BoticasPeruScrapperQueryImp(IScrapperQuery):
    STORE_NAME = "BoticasPeru"
    STORE_LOGO = "BoticasPeru.png"
    STORE_ID = 2
    url = None
    query = None

    def __init__(self, query):
        super().__init__(query)
        self.query = query

    def execute_query(self):
        # Logger.add_to_log(level="error",
        #                  message="Scrapping Boticas Peru Executed : " + self.query)
        url = "https://boticasperu.pe/mageworx_searchsuiteautocomplete/ajax/index/"
        querystring = {"q": self.query}

        payload = ""
        headers = {
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) "
                          "Chrome/116.0.0.0 Safari/537.36"}
        response = requests.request("GET", url, data=payload, headers=headers, params=querystring)
        self.url = response.json().get('result')[1].get('data')[0].get('url')
        return response.json().get('result')[1].get('data')[0]

    def get_result(self) -> ProductScrapped:
        data = self.execute_query()
        html_content = requests.get(self.url).content
        # Crear un objeto BeautifulSoup
        soup = BeautifulSoup(html_content, 'html.parser')
        title = soup.find("meta", property="og:title")
        img = data.get('image')
        price = soup.findAll('span', {'class': 'price'})[0].get_text().replace('S/', '')
        return ProductScrapped(name=title["content"], price=price, store_id=self.STORE_ID, photo=img)

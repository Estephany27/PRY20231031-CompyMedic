import requests
from bs4 import BeautifulSoup

from compumedic.services.ProductDataScrapperService import IScrapperQuery, ProductScrapped


class BoticasHogarYSalud(IScrapperQuery):
    STORE_NAME = "BoticasHogarYSalud"
    STORE_LOGO = "BoticasHogarYSalud.png"
    STORE_ID = 1
    url = None
    query = None

    def __init__(self, query):
        super().__init__(query)
        self.query = query

    def execute_query(self):
        import requests
        url = "https://www.hogarysalud.com.pe/wp-admin/admin-ajax.php"

        querystring = {
            "action": "woodmart_ajax_search",
            "number": 1,
            "post_type": "product",
            "query": self.query
        }

        payload = ""
        headers = {
            "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) "
                          "Chrome/116.0.0.0 Safari/537.36"}
        response = requests.request("GET", url, data=payload, headers=headers, params=querystring)
        return response.json().get('suggestions')

    def get_result(self) -> ProductScrapped:
        data = self.execute_query()
        title = data[0].get('value')
        # Crear un objeto BeautifulSoup para extraer precio del span
        soup = BeautifulSoup(data[0].get('price'), 'html.parser')
        price = soup.findAll('bdi')[0].text.replace('S/', '').strip()
        soup_photo = BeautifulSoup(data[0].get('thumbnail'), 'html.parser')
        img = soup_photo.findAll('img')[0].get('src')
        return ProductScrapped(name=title, price=price, store_id=self.STORE_ID, photo=img)

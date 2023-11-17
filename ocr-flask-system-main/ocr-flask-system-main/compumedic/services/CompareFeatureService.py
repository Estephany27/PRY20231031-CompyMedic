from compumedic.utils.Logger import Logger
from compumedic.services.scrapping.BoticasHogarYSaludQueryImp import BoticasHogarYSalud
from compumedic.services.scrapping.BoticasYSaludQueryImp import BoticasYSaludQueryImp
from compumedic.services.scrapping.MiFarmaQueryImp import MiFarmaQueryImp
from compumedic.services.scrapping.InkaFarmaQueryImp import InkaFarmaQueryImp
from compumedic.services.scrapping.FarmaciaUniversalQueryImp import FarmaciaUniversalQueryImp
from compumedic.services.scrapping.BoticasPeruScrapperQueryImp import BoticasPeruScrapperQueryImp
class Scraper:
    def __init__(self, query):
        self.query = query
        self.scrapers = [
            (BoticasHogarYSalud(self.query)),
            (BoticasYSaludQueryImp(self.query)),
            (MiFarmaQueryImp(self.query)),
            (InkaFarmaQueryImp(self.query)),
            (FarmaciaUniversalQueryImp(self.query)),
            (BoticasPeruScrapperQueryImp(self.query)),
        ]

    def execute_and_log(self, scraper, name, photo, data):
        try:
            scraper.execute_query()
            data.append({
                'name': name,
                'photo': photo,
                'product': scraper.get_result().to_dict()
            })
        except IndexError as e:
            print(f"QueryException: {name} No se encuentra el producto.{e}")
            Logger.add_to_log(f"QueryException: {name} No se encuentra el producto.{e}", str(e))
        except Exception as e:
            Logger.add_to_log("QueryException Rare: ", str(e))

    def get_data(self):
        data = []

        for scraper in self.scrapers:
            self.execute_and_log(scraper, scraper.STORE_NAME, scraper.STORE_LOGO, data)

        return sorted(data, key=lambda x: x['product'].get('price'))

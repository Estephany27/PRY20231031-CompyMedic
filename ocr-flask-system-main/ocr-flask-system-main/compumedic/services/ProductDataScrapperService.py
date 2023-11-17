import json
from compumedic.utils.CurrencyType import format_money
from decimal import Decimal

class ProductScrapped(object):
    name = None
    price = None
    store_id = None
    photo = None

    def __init__(self, name="", price=Decimal(0), store_id=0, photo=""):
        self.name = name
        self.price = round(Decimal(price), 2)
        #si el precio es mas barato es por que se esta considerando por unidad * 10 en este caso
        if (self.price<1):
            self.price = round(Decimal(price)* 10, 2) 
        self.store_id = store_id
        self.photo = photo
        pass

    def to_json(self):
        return json.dumps(self.to_dict())
    
    def get_price(self):
        return self.price

    def to_dict(self):
        return {
        "name": self.name,
        "price": self.price,
        "store_id": self.store_id,
        "photo": self.photo
    }


class IScrapperQuery:
    """ InterfaceQuery for prepare query """
    query = None

    def __init__(self, query="acetaminofen"):
        self.query = query
        print("\nInterfaceQuery: " + self.query)
        pass

    def execute_query(self):
        """ run all routines in order to success scrap needed """

    def get_result(self) -> ProductScrapped:
        """ get data from response
        Returns
        -------
        ProductScrapped
            Class product for getting data
        """



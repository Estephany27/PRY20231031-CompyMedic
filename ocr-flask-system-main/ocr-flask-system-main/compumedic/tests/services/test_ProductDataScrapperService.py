# Services
from compumedic.services.ProductDataScrapperService import ProductScrapped
from compumedic.services.scrapping.BoticasPeruScrapperQueryImp import BoticasPeruScrapperQueryImp
from compumedic.services.scrapping.FarmaciaUniversalQueryImp import FarmaciaUniversalQueryImp

def test_execute_query_get_result_check_response_is_6():
    inst_scrap_bp = BoticasPeruScrapperQueryImp()
    inst_scrap_bp.execute_query()
    assert inst_scrap_bp.get_result().store_id is 6

def test_farm_universal():
    fu = FarmaciaUniversalQueryImp()
    fu.execute_query()
    print(fu.get_result().price)

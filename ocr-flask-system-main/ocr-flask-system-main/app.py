from compumedic import create_app
from compumedic.cache import cache
from compumedic.config import Config
from compumedic.extensions import cors

from compumedic.routes.ErrorRoutes import not_found, method_not_allowed, internal_server_error


app = create_app()


cors.init_app(app)

cache.init_app(app, config={
    "CACHE_TYPE": Config.REDIS_CACHE_TYPE,
    "CACHE_REDIS_HOST": Config.REDIS_HOST,
    "CACHE_REDIS_DB": "0",
    "CACHE_REDIS_PORT": "6379",
    "CACHE_KEY_PREFIX": "compumedic",
    "CACHE_DEFAULT_TIMEOUT": 43200
})
# Registra las rutas de error importadas
app.register_error_handler(404, not_found)
app.register_error_handler(405, method_not_allowed)
app.register_error_handler(500, internal_server_error)


if __name__ == "__main__":
    app.run(host=Config.FLASK_RUN_HOST)

with app.app_context():
    cache.clear()

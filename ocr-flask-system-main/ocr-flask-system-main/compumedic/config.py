from decouple import config


class Config():
    FLASK_RUN_HOST = config('FLASK_RUN_HOST')
    REDIS_HOST= config('REDIS_HOST')
    REDIS_CACHE_TYPE=config('REDIS_CACHE_TYPE')


class DevelopmentConfig(Config):
    DEBUG = True

class ProductionConfig(Config):
    DEBUG = False

config = {
    'development': DevelopmentConfig,
    'production': ProductionConfig
}

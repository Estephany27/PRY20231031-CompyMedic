from flask import Flask

app = Flask(__name__, template_folder="templates")


def create_app():
    from compumedic.routes.IndexRoutes import main as main_blueprint
    from compumedic.routes.CompareFeatureRoutes import compare as compare_blueprint
    from compumedic.routes.ProcessRecipe import upload as upload_blueprint

    app.register_blueprint(main_blueprint, url_prefix='/')
    app.register_blueprint(compare_blueprint, url_prefix='/compare')
    app.register_blueprint(upload_blueprint, url_prefix='/upload')
    return app

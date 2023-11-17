from compumedic.extensions import db


class Product(db.Model):
    __tablename__ = 'products'
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(50))
    description = db.Column(db.String(120))
    photo = db.Column(db.String(120))
    price = db.Column(db.Numeric(10, 2))
    store_id = db.Column(db.Integer, db.ForeignKey('store.id', name='fk_product_store_id'))


class Store(db.Model):
    __tablename__ = 'stores'
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(50))
    description = db.Column(db.String(120), )
    photo = db.Column(db.String(120))


class StoreModelView(Store):
    meta_data = None

    def __init__(self, id, name, logo):
        self.id = id
        self.name = name
        self.logo = logo
        pass

    def set_meta(self, data):
        self.meta_data = data


def get_store_by_id(id_product):
    with db.session.begin():
        store = db.session.query(Store).filter_by(id=id_product).first()
        return StoreModelView(store.id, store.name, logo=store.photo)

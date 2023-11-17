from urllib.parse import urlparse


def uri_validator(x):
    try:
        result = urlparse(x)
        return all([result.scheme, result.netloc])
    except urlparse as e:
        return False

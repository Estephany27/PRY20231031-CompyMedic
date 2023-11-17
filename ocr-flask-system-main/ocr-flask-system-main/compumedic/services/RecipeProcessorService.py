import pytesseract
from cleantext import clean
import string


class RecipeProcessor:
    def __init__(self, route_glossary='compumedic/services/spa.user-words'):
        self.route_glossary = route_glossary
        self.glossary_words = self._read_glossary_and_return_list()

    def _read_glossary_and_return_list(self):
        with open(self.route_glossary) as f:
            glossary = f.read()
        return glossary.split("\n")

    def _extract_raw_text_from_recipe(self, route_img_recipe):
        custom_config = r'--oem 3 --psm 6 --user-words "compumedic/services/spa.user-words"'
        raw_text = pytesseract.image_to_string(
            route_img_recipe, lang='spa', config=custom_config)
        return raw_text

    def _clean_raw_text(self, raw_text):
        return clean(raw_text, fix_unicode=True, to_ascii=True, lower=True)

    def extract_words_from_cleaned_text_and_return_list(self, cleaned_text):
        return [word.strip(string.punctuation) for word in cleaned_text.split() if
                word.strip(string.punctuation).isalnum() and len(word) > 9]

    def _remove_duplicates_on_list(self, list_words):
        return list(set(list_words))

    def return_glossary_words(self, list_no_duplicated_items):
        result = [word for word in list_no_duplicated_items if word in self.glossary_words]
        return result

    def process_recipe(self, route_uploaded_img_recipe="test.jpg"):
        raw_text = self._extract_raw_text_from_recipe("./compumedic/static/uploads/" + route_uploaded_img_recipe)
        cleaned_text = self._clean_raw_text(raw_text)
        words_extracted = self.extract_words_from_cleaned_text_and_return_list(cleaned_text)
        list_no_duplicated_items = self._remove_duplicates_on_list(words_extracted)
        only_glossary_words = self.return_glossary_words(list_no_duplicated_items)
        print("Procesando Imagen OCR")
        print(f'kywords_extracted: {list_no_duplicated_items}, glossary_kywords: {only_glossary_words}')
        
        return {'kywords_extracted': list_no_duplicated_items, 'glossary_kywords': only_glossary_words}

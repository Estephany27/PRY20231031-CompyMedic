import os.path
from flask import Blueprint, request, render_template

from compumedic.services.RecipeProcessorService import RecipeProcessor
# Import Logger
from compumedic.utils.Logger import Logger

upload = Blueprint('process_recipe_blueprint',
                   __name__, static_folder='../static')

# Create a logger instance
logger = Logger()


@upload.post('/')
def process():
    """
    Display the upload form and handle file upload.
    """
    try:
        ocr_inst = RecipeProcessor()
        uploaded_file = request.files['file']

        if uploaded_file.filename != '':
            uploaded_file.save(os.path.join(
                upload.static_folder, "uploads/") + uploaded_file.filename)
            return render_template('recipe.jinja2',
                                   lists=ocr_inst.process_recipe(
                                       uploaded_file.filename),
                                   img_filename=uploaded_file.filename)
        else:
            return render_template('upload_recipe.jinja2')
    except Exception as e:
        # Log the exception
        logger.add_to_log("critical", f"An error occurred: {str(e)}")
        # Handle the error or return an appropriate response
        return render_template('error/error_template.jinja2', error_message="An error occurred while processing the request.")

<?php

include_once "FilterSelect.php";

class FilterCategory extends FilterMultiSelect {

	public function __construct($id,
								$captionText,
								$defaultValue,
								$value,
								$dbTable,
								$dbColumnName,
								$dbValueType,
								$rules,
								$inlineEdit,
								$options) {

		$this->type 		= "category";
		$this->id 			= $id;	//filter_category
		$this->captionText 	= $captionText; //Category
		$this->defaultValue = $defaultValue;
		$this->value 		= $value; //category id set by user
		$this->dbTable 		= $dbTable;	//product_to_category
		$this->dbColumnName = $dbColumnName; //category_id
		$this->dbValueType 	= $dbValueType; //integer
		$this->rules 		= $rules; //false
		$this->autocomplete = true;
		$this->inlineEdit 	= $inlineEdit; //true
		$this->options 		= $options;
		$this->sortable		= false;		
	}

	//Convert category ids to editable list of category names using $value as array of categories
	public function editHtmlInput($id, $value) {
		$categories = $value;

		$html = '<input type="text" name="'.$id.'" value="" id="input-category" style="width:100%" /><div id="'.$id.'" class="well well-sm" style="overflow: auto;">';
		foreach ($categories as $category) { 
			$category["name"] = str_replace(";arrow;", ">", $category["name"]);


          	$html .= '<div id="category' . $category['category_id'] . '"><i class="fa fa-minus-circle fa-lg"style="cursor:pointer; color:red"></i>' . $category['name'] . '<input type="hidden" name="category_id[]" value="' . $category['category_id'] .'" /></div>';
        } 

		return $html . '</div>';
	}

	public function getJsScript($token) {
		return $this->getJSAutocompleteScript($token);
	}

	protected function getJSAutocompleteScript($token) {
		$sc = '<script type="text/javascript">';
		$sc .= 	"$('input[name=filter_category_id_edit]').autocomplete({"
				. "delay: 500,"
				. "source: function(request, response) { $.ajax({"
   				. "url: 'index.php?route=catalog/category/autocomplete&token=" . $token . "&filter_name=' +  encodeURIComponent(request),"
    			. "dataType: 'json',"
    			. "success: function(json) {"   
			        . "response($.map(json, function(item) {"
			            . "return {label: item.name, value: item.category_id}}));"
    				."}"
  				. "});"
					. "}, select: function(item) {"
  					. "$('#category' + item.value).remove();"
					. "$('#filter_category_id_edit').append('<div id=\"category' + item.value + '\"><i class=\"fa fa-minus-circle fa-lg\" style=\"cursor:pointer; color:red;\"></i>' + item.label + '<input type=\"hidden\" name=\"category_id[]\" value=\"' + item.value + '\" /></div>');"
					. "return false;"
				. "}});";
		$sc .=  "$('#filter_category_id_edit div i').on('click', function() {"
				. "$(this).parent().remove();});";	
		$sc .= "</script>";
		return $sc;
	}

	//Convert category ids to read only list of category names
	public function getCategoriesList($categoryIds, $modelCategory) {
		$categories = $this->getCategoriesByCategoryIds($categoryIds, $modelCategory);

		$html = '<div class="scrollbox">';
		$class='odd';
		foreach ($categories as $category) { 
          	$class = ($class == 'even' ? 'odd' : 'even');
          	$html .= '<div class="category-value ' . $class . '" data-id="'.$category['category_id'].'">' . $category['name'] . '</div>';
        } 

        return $html . '</div>';
	}

	public function getCategoriesListByProductId($productId, $modelProduct, $modelCategory) {
		$categories = $modelProduct->getProductCategories($productId);

		return $this->getCategoriesList($categories, $modelCategory);
	}

	public function getCategoriesByProductId($productId, $modelProduct, $modelCategory) {
		$categories = $modelProduct->getProductCategories($productId);

		return $this->getCategoriesByCategoryIds($categories, $modelCategory);
	}

	public function getCategoriesByCategoryIds($categoryIds, $modelCategory) {
		$result = array();
		foreach ($categoryIds as $category_id) {
			$category_info = $modelCategory->getCategory($category_id);

			if ($category_info) {
				$result[] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' > ' : '') . $category_info['name']
				);
			}
		}

		return $result;
	}
}

?>
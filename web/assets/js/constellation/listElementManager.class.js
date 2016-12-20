/**
 * This file is part of the MonVoisinFaitDuBio project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2016 Sebastian Castro - 90scastro@gmail.com
 * @license    MIT License
 * @Last Modified time: 2016-12-13
 */
function ListElementManager() 
{
	
}

ListElementManager.prototype.draw = function () 
{
	$('#ElementList li').remove();

	var element, elementsPlacesToDisplay = [], elementsProducteurOrAmapToDisplay = [];
	for(var i = 0; i < App.getConstellation().getStars().length; i++)
	{
		element = App.getConstellation().getStars()[i].getElement();
		if (element.isProducteurOrAmap())
		{
			if (elementsProducteurOrAmapToDisplay.indexOf(element) == -1) elementsProducteurOrAmapToDisplay.push(element);
		}			
		else
		{
			if (elementsPlacesToDisplay.indexOf(element) == -1) elementsPlacesToDisplay.push(element);
		}
			
	}

	elementsProducteurOrAmapToDisplay.sort(compareDistance);
	elementsPlacesToDisplay.sort(compareDistance);		

	for( i = 0; i < elementsPlacesToDisplay.length; i++)
	{
		element = elementsPlacesToDisplay[i];
		$('#ElementList #places-end-container').before(element.getHtmlRepresentation());
		createListenersForElementMenu($('#element-info-'+element.id +' .menu-element'));	
	}

	for( i = 0; i < elementsProducteurOrAmapToDisplay.length; i++)
	{
		element = elementsProducteurOrAmapToDisplay[i];
		$('#ElementList #producteurAmap-end-container').before(element.getHtmlRepresentation());
		createListenersForElementMenu($('#element-info-'+element.id +' .menu-element'));	
	}	

	$('#ElementList ul').animate({scrollTop: '0'}, 500).collapsible({
      accordion : true 
    });
	
};

function compareDistance(a,b) 
{  
  if (a.distance == b.distance) return 0;
  return a.distance < b.distance ? -1 : 1;
}


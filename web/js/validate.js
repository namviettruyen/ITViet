function ValidateRequiredFields(frm, arr)
{
	for (i = 0; i < arr.length; i++)
	{
		var e = frm.elements[arr[i]];
		if (e.value.length <= 0)
			return false;
	}	
	return true;
}
function ValidateRegular(source, reg)
{
	value = source.value;
	if (value.length == 0)
		return true;
	var rx = new RegExp(reg);
	var matches = rx.exec(value);
    return (matches != null && value == matches[0]);
}
function ValidateEmail(source)
{
	if  (!ValidateRegular(source, "\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*"))
	{
		source.select();
		source.focus();
		return false;
	}
	return true;
}
function ValidatePhone(source)
{
	if (!ValidateRegular(source, "((\\(\\d{3}\\) ?)|(\\d{3}-))?\\d{3}-\\d{4}"))
	{
		source.select();
		source.focus();
		return false;
	}
	return true;
}
function IsInteger(source)
{
	if (!ValidateRegular(source, "\\d*"))
	{
		source.select();
		source.focus();
		return false;
	}
	return true;
}
function ValidateURL(source)
{
	if (!ValidateRegular(source, "((\\(\\d{3}\\) ?)|(\\d{3}-))?\\d{3}-\\d{4}"))
	{
		source.select();
		source.focus();
		return false;
	}
	return true;
}
/*************************************************************************\
CheckCardNumber(form)
function called when users click the "check" button.
\*************************************************************************/
/*************************************************************************\
boolean isNum(String argvalue)
return true if argvalue contains only numeric characters,
else return false.
\*************************************************************************/
function isNum(argvalue) 
{
	argvalue = argvalue.toString();
	if (argvalue.length == 0)
	return false;
	for (var n = 0; n < argvalue.length; n++)
		if (argvalue.substring(n, n+1) < "0" || argvalue.substring(n, n+1) > "9")
			return false;
	return true;
}
/*************************************************************************\
boolean luhnCheck([String CardNumber])
return true if CardNumber pass the luhn check else return false.
Reference: http://www.ling.nwu.edu/~sburke/pub/luhn_lib.pl
\*************************************************************************/
function luhnCheck() 
{
	var argv = luhnCheck.arguments;
	var argc = luhnCheck.arguments.length;
	var CardNumber = argc > 0 ? argv[0] : this.cardnumber;
	if (! isNum(CardNumber)) 
	{
		return false;
	}
	var no_digit = CardNumber.length;
	var oddoeven = no_digit & 1;
	var sum = 0;
	for (var count = 0; count < no_digit; count++) 
	{
		var digit = parseInt(CardNumber.charAt(count));
		if (!((count & 1) ^ oddoeven)) 
		{
			digit *= 2;
			if (digit > 9)
			digit -= 9;
		}
		sum += digit;
	}
	if (sum % 10 == 0)
		return true;
	else
		return false;
}
/*************************************************************************\
ArrayObject makeArray(int size)
return the array object in the size specified.
\*************************************************************************/
function makeArray(size) 
{
	this.size = size;
	return this;
}
/*************************************************************************\
CardType setCardNumber(cardnumber)
return the CardType object.
\*************************************************************************/
function setCardNumber(cardnumber) 
{
	this.cardnumber = cardnumber;
	return this;
}
/*************************************************************************\
CardType setCardType(cardtype)
return the CardType object.
\*************************************************************************/
function setCardType(cardtype) 
{
	this.cardtype = cardtype;
	return this;
}
/*************************************************************************\
CardType setExpiryDate(year, month)
return the CardType object.
\*************************************************************************/
function setExpiryDate(year, month) 
{
	this.year = year;
	this.month = month;
	return this;
}
/*************************************************************************\
CardType setLen(len)
return the CardType object.
\*************************************************************************/
function setLen(len) 
{
	// Create the len array.
	if (len.length == 0 || len == null)
		len = "13,14,15,16,19";
	var tmplen = len;
	n = 1;
	while (tmplen.indexOf(",") != -1) 
	{
		tmplen = tmplen.substring(tmplen.indexOf(",") + 1, tmplen.length);
		n++;
	}
	this.len = new makeArray(n);
	n = 0;
	while (len.indexOf(",") != -1) 
	{
		var tmpstr = len.substring(0, len.indexOf(","));
		this.len[n] = tmpstr;
		len = len.substring(len.indexOf(",") + 1, len.length);
		n++;
	}
	this.len[n] = len;
	return this;
}
/*************************************************************************\
CardType setRules()
return the CardType object.
\*************************************************************************/
function setRules(rules) 
{
	// Create the rules array.
	if (rules.length == 0 || rules == null)
		rules = "0,1,2,3,4,5,6,7,8,9";
	  
	var tmprules = rules;
	n = 1;
	while (tmprules.indexOf(",") != -1) 
	{
		tmprules = tmprules.substring(tmprules.indexOf(",") + 1, tmprules.length);
		n++;
	}
	
	this.rules = new makeArray(n);
	n = 0;
	
	while (rules.indexOf(",") != -1) 
	{
		var tmpstr = rules.substring(0, rules.indexOf(","));
		this.rules[n] = tmpstr;
		rules = rules.substring(rules.indexOf(",") + 1, rules.length);
		n++;
	}
	this.rules[n] = rules;
	return this;
}
/////////////////////////////////////////////////////////////////
//This function is check include field for a textbox
///////////////////////////////////////////////////////////
function VKCheckRequireFieldNull(object,strMesssage)
{
	if(object != null)
	{
		return VKCheckRequireField(object,strMesssage)
	}
	else
	{
		return "";
	}
	
}
/////////////////////////////////////////////////////////////////
//This function is check include field for a textbox
///////////////////////////////////////////////////////////
function VKCheckComboRequireFieldNull(object,strMesssage)
{
	if(object != null)
	{
		return VKCheckCombo(object,strMesssage)
	}
	else
	{
		return "";
	}
	
}
/////////////////////////////////////////////////////////////////
//This function is check include field for a textbox
///////////////////////////////////////////////////////////
function VKCheckIsNumberFieldNull(object,strMesssage)
{
	var strReturnValue = "";
	if(object != null)
	{
		if(object.value != "")
		{
			if(isNaN(object.value))
			{
				strReturnValue = strMesssage;
			}
		}
	}
	return strReturnValue;
	
}
function VKCheckRequireFieldNoFocusNull(object,strMesssage){
	if(object != null)
	{
		return VKCheckRequireFieldNoFocus(object,strMesssage)
	}
	else
	{
		return "";
	}
}
function VKCheckRequireFieldNoFocusNullOver10Char(object,strMesssage){
	if(object != null)
	{
		
		return VKCheckRequireFieldNoFocusOver10Char(object,strMesssage)
	}
	else
	{
		return "";
	}
}
/////////////////////////////////////////////////////////////////
//This function is check include field for a textbox
///////////////////////////////////////////////////////////
function VKCheckRequireFieldExNull(object,strMesssage)
{
	if(object != null)
	{
		return VKCheckRequireFieldEx(object,strMesssage)
	}
	else
	{
		return "";
	}
	
}
function VKCheckRequireFieldNoFocus(object,strMesssage)
{
	var id = object.id;
	var value = object.value;
	if(typeof(FCKeditorAPI) != "undefined")
	{
		if(FCKeditorAPI.GetInstance(id) != null)
		{
			value = FCKeditorAPI.GetInstance(id).GetXHTML();
		}
	}
	if(IsLesser(value,1))
	{
		return remove_vietnamese_accents(strMesssage);
		
	}
	else{
		return "";
	}
}
function VKCheckRequireFieldNoFocusOver10Char(object,strMesssage)
{
	var id = object.id;
	var value = object.value;
	
	if(typeof(FCKeditorAPI) != "undefined")
	{
		if(FCKeditorAPI.GetInstance(id) != null)
		{
			value = FCKeditorAPI.GetInstance(id).GetXHTML();
		}
	}
	if(IsLesser(value,10))
	{
		return strMesssage;
		
	}
	else
	{
		return "";
	}
}
/////////////////////////////////////////////////////////////////
//This function is check include field for a textbox
///////////////////////////////////////////////////////////
function IsLesser(value,length)
{
	var strReturnValue = value;
	var arrReplace = new Array("<html>","</html>","<head>","</head>","<body>","</body>","<Br>","<br/>","<p>","</p>","<div>","</div>","<span>","</span>","&nbsp;"," ","\r\n");
	var iMaxLoop = 1000;
	var iIndex = 0;
	for(i = 0; i < arrReplace.length;i++)
	{
		var iIndex = 0;
		while((strReturnValue.toLowerCase().indexOf(arrReplace[i].toLowerCase()) >= 0) && (iIndex < iMaxLoop))
		{
			strReturnValue = strReturnValue.replace(arrReplace[i],"");
			strReturnValue = strReturnValue.replace(arrReplace[i].toLowerCase(),"");
			strReturnValue = strReturnValue.replace(arrReplace[i].toUpperCase(),"");
			iIndex++;
		}
	}
	if(strReturnValue.length < length)
	{
		return true;	
	}
	else
	{
		return false;	
	}
}
/////////////////////////////////////////////////////////////////
//This function is check include field for a textbox
///////////////////////////////////////////////////////////
function VKCheckRequireField(object,strMesssage){
	if(object.value.length == 0){
		object.focus();
		return remove_vietnamese_accents(strMesssage);
		
	}
	else{
		return "";
	}
}
/////////////////////////////////////////////////////////////////
//This function is check include field for a textbox
///////////////////////////////////////////////////////////
function VKCheckRequireFieldEx(object,strMesssage){
	if((object.value.length == 0) || (object.value == "0"))
	{
		return strMesssage;
		
	}
	else{
		return "";
	}
}
////////////////////////////////////////////////////////////////////////
//This function is check for compare 2 control
///////////////////////////////////////////////////////////////////////
function VKCheckCompareField(objControl1,objControl2,strMessage){
	if(objControl1.value != objControl2.value){
		objControl2.focus()
		return strMessage;
	}
	else{
		return "";
	}
}
////////////////////////////////////////////////////////////////////////
//This function is check for compare 2 control
///////////////////////////////////////////////////////////////////////
function VKCompareDate(date1,date2,strMessage)
{
	if(date1 >= date2){
		return strMessage;
	}
	else{
		return "";
	}
}
///////////////////////////////////////////////////
//This function is check email but it only return a message
////////////////////////////////////////////////////////
function VKCheckEmailNull(object,strMesssage)
{
	if(object != null)
	{
		return VKCheckEmail(object,strMesssage)
	}
	else
	{
		return "";
	}
	
}
function VKCheckEmail(object,strMessage){
	if(object.value.length > 0){
		if(ValidateEmail(object)){
			return "";
		}
		else
		{
			return remove_vietnamese_accents(strMessage);
			object.focus();
		}
	}
	else{
		return "";
	}
}
///////////////////////////////////////////////////
//This function is check zipcode but it only return a message
////////////////////////////////////////////////////////
function GHValidateZipCode(source)
{
	if (!ValidateRegular(source, "\\d{5}(-\\d{4})?"))
	{
		source.select();
		source.focus();
		return false;
	}
	return true;
}
///////////////////////////////////////////////////
//This function is check website but it only return a message
////////////////////////////////////////////////////////
function VKCheckWebsite(object, strMessage)
{
	if(object.value.length > 0){
		if(ValidateWebsite(object)){
			return "";
		}
		else
		{
			return strMessage
			object.focus();
		}
	}
	else{
		return "";
	}
}
function ValidateWebsite(source)
{
	if (!ValidateRegular(source, "(http://)?([\\w-]+\\.)+[\\w-]+(/[\\w- ./?%&=]*)?"))
	{
		source.select();
		source.focus();
		return false;
	}
	return true;
}
/////////////////////////////////////////////////////////
function VKCheckPhone(object,strMessage){
	if(object.value.length > 0){
		if(ValidatePhone(object)){
			return "";
		}
		else
		{
			return strMessage
			object.focus();
		}
	}
	else{
		return "";
	}
}
/////////////////////////////////////////////////////////////////////
function VKCheckZipCode(object,strMessage){
	if(object.value.length > 0){
		if(GHValidateZipCode(object)){
			return "";
		}
		else
		{
			object.focus();
			return strMessage;
			
		}
	}
	else{
		return "";
	}
}
//////////////////////////////////////////////////////////////////
function VKCheckCombo(object,strMessage)
{
	if(object.options.length > 1)
	{
		if(object.selectedIndex == 0)
		{
			return remove_vietnamese_accents(strMessage);
			object.focus();
		}
		else
		{
			return "";
		}
	}
	else
	{
		return "";
	}
}
//////////////////////////////////////////////////////////////////
function VKCheckListBox(object,strMessage){
	if(object.length == 0){
		return strMessage
		object.focus();
	}
	else{
		return "";
	}
}
function GH2CheckListBox(object1, object2, strMessage)
{
	if((VKCheckListBox(object1, strMessage).length > 0) && (VKCheckListBox(object2, strMessage).length > 0))
	{
		return strMessage;
	}
	else{
		return "";
	}
}
function VKCheckEqual(object1, object2, strMessage)
{
	if(object1.value != object2.value)
	{
		return remove_vietnamese_accents(strMessage);
	}
	else{
		return "";
	}
}
/////////////////////////////////////////////////////////////////////
function VKCheckCardNumber(iCardType, strCardNum, strCardExpMonth, strCardExpYear) 
{
	var tmpyear;
	
	var today = new Date();
	
	if (strCardExpYear < today.getYear())
	{
//		alert("The Expiration Year is not valid. It must be greater than or equals " + today.getYear());
		return  "The Expiration Year is not valid. It must be greater than or equals " + today.getYear();
//		return false;
	}
		
	var tmpmonth;
	tmpmonth  = strCardExpMonth;
	tmpyear = 	strCardExpYear;
	// The following line doesn't work in IE3, you need to change it
	// to something like "(new CardType())...".
	 //if (!CardType().isExpiryDate(tmpyear, tmpmonth)) 
	if (!(new CardType()).isExpiryDate(tmpyear, tmpmonth)) 
	{
	//	alert("This card has already expired.");
		return  "This card has already expired.";
//		return false;
	}
	
	
	var card = Cards[iCardType].getCardType();
	var retval = Cards[iCardType].checkCardNumber(strCardNum,tmpyear,tmpmonth);
	cardname = "";
	if (retval)
	//	return true;
		return "";
	else 
	{
		// The cardnumber has the valid luhn checksum, but we want to know which
		// cardtype it belongs to.
		for (var n = 0; n < Cards.size; n++) 
		{
			if (Cards[n].checkCardNumber(strCardNum, tmpyear, tmpmonth)) 
			{
				cardname = Cards[n].getCardType();
				break;
			}
		}
		if (cardname.length > 0) 
		{
		//	alert("This looks like a " + cardname + " number, not a " + card + " number.");
			return "This looks like a " + cardname + " number, not a " + card + " number.";
		//	return false;
		}
		else 
		{
	//		alert("This card number is not valid.");
			return "This card number is not valid.";
	//		return false;
		}
   }
}
function VKCheckFileType(objControl,strMessage,strFileType ){
	if(objControl.value == "")
	{
		return "";
	}
	else{
		if(checkFile(objControl.value, strFileType))
		{
			return "";
		}
		else
		{
			objControl.select();
			objControl.focus();
			return strMessage;
		}
	}
}
////////////////////////////////////////////////////////////////////////
//This function is check for include file
///////////////////////////////////////////////////////////////////////
	function checkFile(strValue, strFileType)
	{
		var strExtension = strValue.substr((strValue.lastIndexOf(".") + 1), strValue.length);
		var arrExtension = strFileType.split(",");
		var bFound = false;
		for(var i = 0; i < arrExtension.length;i++)
		{
			if(strExtension.toLowerCase() == arrExtension[i].toLowerCase())
			{
				bFound = true;
			}
		}
		return bFound;
	}
/////////////////////////////////////////////////////////////
// End of  code
///////////////////////////////////////////////////////


function str_replace(search, replace, str){
  var ra = replace instanceof Array, sa = str instanceof Array, l = (search = [].concat(search)).length, replace = [].concat(replace), i = (str = [].concat(str)).length;
  while(j = 0, i--)
    while(str[i] = str[i].split(search[j]).join(ra ? replace[j] || "" : replace[0]), ++j < l);
  return sa ? str : str[0];
}
 
function remove_vietnamese_accents(str)
{
  accents_arr= new Array(
    "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
    "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề",
    "ế","ệ","ể","ễ",
    "ì","í","ị","ỉ","ĩ",
    "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ",
    "ờ","ớ","ợ","ở","ỡ",
    "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
    "ỳ","ý","ỵ","ỷ","ỹ",
    "đ",
    "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
    "Ằ","Ắ","Ặ","Ẳ","Ẵ",
    "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
    "Ì","Í","Ị","Ỉ","Ĩ",
    "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ",
    "Ờ","Ớ","Ợ","Ở","Ỡ",
    "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
    "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
    "Đ"
  );
 
  no_accents_arr= new Array(
    "a","a","a","a","a","a","a","a","a","a","a",
    "a","a","a","a","a","a",
    "e","e","e","e","e","e","e","e","e","e","e",
    "i","i","i","i","i",
    "o","o","o","o","o","o","o","o","o","o","o","o",
    "o","o","o","o","o",
    "u","u","u","u","u","u","u","u","u","u","u",
    "y","y","y","y","y",
    "d",
    "A","A","A","A","A","A","A","A","A","A","A","A",
    "A","A","A","A","A",
    "E","E","E","E","E","E","E","E","E","E","E",
    "I","I","I","I","I",
    "O","O","O","O","O","O","O","O","O","O","O","O",
    "O","O","O","O","O",
    "U","U","U","U","U","U","U","U","U","U","U",
    "Y","Y","Y","Y","Y",
    "D"
  );
 
  return str_replace(accents_arr,no_accents_arr,str);
}

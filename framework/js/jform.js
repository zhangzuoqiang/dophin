/*
 * Javascript Form Plugin
 * version: 0.1.0 (01/25/2011)
 * @requires jQuery v1.4 or later
 *
 * Examples at: http://dophin.co/
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id$
 */
(function($) {

/**
 *@param rules 
 *@param newOptions 
 *@param extFunction execute before submit form 
 */
$.fn.jform = function(rules, newOptions, extFunction) {
	var tagName = $(this).attr('tagName');
	if (tagName != 'FORM') return false;
	
	var errors = [];
	var nonExistsElement = [];
		
	var options = {
		
		ajax	: {
			use		: false,
			method	: 'POST',
			dataType: 'script',
			target	: '',
			callback: function(){}
		},
		
		offset:              {x:5, y:0},	// offset position for error message tooltip
		position:            {x:'right', y:'center'}, // error message placement x:left|center|right  y:top|center|bottom
		
		ruleDelimiter	: ',',
		paramsDelimiter	: ';',
		
		imeModeRules : ['required','nonzero','equalto','maxlength','minlength','rangelength','Chinese'],
		realTimeRules: ['integer', 'digit', 'alpha', 'alphanum', 'min', 'max'],
				
		msgTmplate	 : '<div class="{errMsgClass}">{message}</div>',
		errMsgClass  : 'jform_errmsg',
		closeIconClass : 'jform_close_icon',
		
		showErrMsgSpeed:    'normal',
		
		messages	 : {
			unknow		: "验证规则发现未知错误.",
			needselect	: "必须选择",
			required	: "不能为空.",
			nonzero		: "不能为0",
			integer		: "必须为整数.",
			number		: "必须为数字.",
			digit		: "必须为纯数字.",
			decimal		: "必须为包含小数的数字",
			alpha		: "必须为字母.",
			alphanum	: "必须为字母或数字.",
			equalto		: "必须与 {2} 相同的值.",
			differs		: "必须与 {2} 不同的值.",
			minlength	: "不能少于 {1} 个字符.",
			maxlength	: "不能超过 {1} 个字符.",
			rangelength : "字符长度必须介于 {1} 和 {2} 之间",
			min			: "最小值不能低于 {1}",
			max			: "最大值不能超过 {1}",
			between		: "必须输入一个介于 {1} 和 {2} 之间的值.",
			minInt		: "必须为最小值不能低于 {1} 的整数",
			maxInt		: "必须为最大值不能超过 {1} 的整数",
			betweenInt	: "必须输入一个介于 {1} 和 {2} 之间的整数值.",
			Chinese		: "必须为汉字字符",
			idCard		: "非法的身份证号格式",
			telephone	: "非法的电话格式",
			mobile		: "非法的手机号格式",
			zipcode		: "非法的邮政号码格式",
			qq			: "非法的QQ号码格式",
			email		: "非法的Email格式",
			url			: "非法的URL格式",
			date		: "非法的日期格式",
			time		: "非法的时间格式",
			dateTime	: "非法的日期时间格式",
			ip4			: "非法的IPV4格式"
		},
		regex	: {
			integer		: /^-?[1-9]+[\d]*?$/,
			number		: /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/,
			digit		: /^\d+$/,
			decimal		: /^[-\+]?\d+(\.\d+)$/,
			alpha   	: /^[A-Za-z]+$/,
			alphanum   	: /^[A-Za-z0-9]+$/,
			min			: /^[-\+]?\d+(\.\d+)?$/,
			max			: /^[-\+]?\d+(\.\d+)?$/,
			Chinese		:  /^[\u0391-\uFFE5]+$/,
			idCard		: /(^\d{15}$)|(^\d{17}[0-9Xx]$)/,
			telephone	: /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
			mobile		: /^((\(\d{2,3}\))|(\d{3}\-))?(13\d|15[3689])\d{8}jq/,
			zipcode		: /^[1-9]\d{5}$/,
			money		: /^\d+(\.\d+)?$/,
			qq			: /^[1-9]\d{4,9}$/,
			email		: /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/,
			url			: /^(http|ftp|https):\/\/[A-Za-z0-9\-]+(\.[A-Za-z0-9\-]+)+([A-Za-z0-9\-\.,@?^=%&amp;:\/~\+#]*[\w\-\@?^=%&amp;\/~\+#])?$/,
			date		: /^\d{4}-\d{1,2}-\d{1,2}$/,
			time		: /^\d{1,2}:\d{1,2}:\d{1,2}$/,
			dateTime	: /^\d{4}-\d{1,2}-\d{1,2}\s\d{1,2}:\d{1,2}:\d{1,2}$/,
			image		: /\.(jpg|jpeg|png|gif|bmp)$/i
		}
	};
	
	//var ruleList = $.merge( options.imeModeRules, options.numericRules, options.integerRules );
	
	// 是否关闭输入法
	var tmp = '';
	$.each(rules, function(fieldId, n){
		if ($('#'+fieldId).length == 0) {
			alert('element [' + fieldId + '] is not exists!');
			nonExistsElement[nonExistsElement.length] = fieldId;
		}
		var _rule = n.rule.split(options.ruleDelimiter);
		$.each(_rule, function(ii, nn){
			var params = nn.match(/^(.*?)\[(.*?)\]/);
			if(params && params.length == 3){
				var validatorName = $.trim(params[1]);
				params = params[2].split(options.paramsDelimiter);
			}
			else{
				var validatorName = $.trim(nn);
				params = [];
			}
			
			// 限制不能输入中文
			if (-1 == $.inArray(validatorName,options.imeModeRules)) {
				$('#'+fieldId).css("ime-mode", "disabled");
			}
			// 需要处理键盘输入事件
			if (-1 != $.inArray(validatorName,options.realTimeRules)) {
				$('#'+fieldId).keypress(function(e){
					var str = $(this).val() + '' + String.fromCharCode(e.which);
					var result = options.regex[validatorName].exec(str);
					if (!result) {
						var errMsg = options.messages[validatorName];
						//errMsg = errMsg.replace(new RegExp("\\{" + j + "\\}", "g"), params[j]);
						_showErrMsg(fieldId, errMsg);
						return false;
					} else {
						return true;
					}
				});
			}
		});
		
		$('#'+fieldId).blur(function(e){
			validate(fieldId);
		});
	});
	
	
	if(newOptions)
	$.extend(true, options, newOptions);
	
	// gets element value	
	_getValue = function(element){

		var ret = {};

		// checkbox
		if(element.is('input:checkbox')){
			ret['value'] = element.attr('name') ? ret['selectedInGroup'] = $('input:checkbox[name="' + element.attr('name') + '"]:checked').length : element.attr('checked');
		}
		else if(element.is('input:radio')){
			ret['value'] = element.attr('name') ? ret['value'] = $('input:radio[name="' + element.attr('name') + '"]:checked').length : element.val();
		}
		else if(element.is('select')){
			ret['selectedInGroup'] =  $("option:selected", element).length;
			ret['value'] = element.val();
		}
		else if(element.is(':input')){
			ret['value'] = element.val();
		} else {
			
		}

		return ret;
	};
	
	// 在页面上显示错误信息
	_showErrMsg = function(elementId, msg) {
		// if error message already exists remove it from DOM
		var element = $('#'+elementId);
		_removeErrMsg(element);

		msg_container = $('<div class="jFErrMsgContainer"></div>').css('position','absolute');
		if (rules[elementId].msg_container) {
			var selfContainer = $('#'+rules[elementId].msg_container);
			msg_container.appendTo(selfContainer);
		} else {
			msg_container.insertAfter(element);
		}
		element.data("errMsg.jF", msg_container);
		
		

		var messageHtml =  '<span id="jFErrMsgContainer_msg">' + msg + '</span>\n';

		var messageTpl = '<div>{message}<span class="'+options.closeIconClass+'" onclick="$(this).closest(\'.'+options.errMsgClass +'\').css(\'visibility\', \'hidden\');">x</span></div>';
		messageHtml = messageTpl.replace('{message}', messageHtml);

		// make tooltip from template
		var tooltip = $(options.msgTmplate.replace('{errMsgClass}', options.errMsgClass).replace('{message}', messageHtml));
		
		tooltip.appendTo(msg_container);
		if (rules[elementId].msg_container) {
			var pos = _getErrMsgPosition(element, tooltip, selfContainer);
			tooltip.css({visibility: 'visible', position: 'absolute', top: pos.top, left: pos.left}).fadeIn(options.showErrMsgSpeed);
			// 有指定信息提示容器,则不保存容器,只保存窗口内
		} else {
			var pos = _getErrMsgPosition(element, tooltip);
			tooltip.css({visibility: 'visible', position: 'absolute', top: pos.top, left: pos.left}).fadeIn(options.showErrMsgSpeed);
		}
	};

	// removes error message from DOM
	_removeErrMsg = function(element){
		var existingMsg = element.data("errMsg.jF");
		if(existingMsg){
			existingMsg.remove();
		}
	};

	// calculates error message position
	_getErrMsgPosition = function(element, tooltip, container){
		var top = 0;
		var left = 0;
		var tooltipContainer = element.data("errMsg.jF");
		if (!tooltipContainer) {
			return {top: top, left: left};
		}
		if (container) {
			top = - ((tooltipContainer.offset().top - container.offset().top) + tooltip.outerHeight() - options.offset.y);
			left = container.offset().left - tooltipContainer.offset().left + options.offset.x;
		} else {
			top  = - ((tooltipContainer.offset().top - element.offset().top) + tooltip.outerHeight() - options.offset.y);
			left = (element.offset().left + element.outerWidth()) - tooltipContainer.offset().left + options.offset.x;
		}
		x = options.position.x;
		y = options.position.y;

		// adjust Y
		if(!container && (y == 'center' || y == 'bottom')){
			var height = tooltip.outerHeight() + element.outerHeight();
			if (y == 'center') 	{top += height / 2;}
			if (y == 'bottom') 	{top += height;}
		} else if (container && (y == 'center' || y == 'bottom')) {
			var height = tooltip.outerHeight() + container.outerHeight();
			if (y == 'center') 	{top += height / 2;}
			if (y == 'bottom') 	{top += height;}
		}

		// adjust X
		if(!container && (x == 'center' || x == 'left')){
			var width = element.outerWidth();
			if (x == 'center') 	{left -= width / 2;}
			if (x == 'left')  	{left -= width;}
		} else if (container && (x == 'center' || x == 'left')) {
			var width = container.outerWidth();
			if (x == 'center') 	{left -= width / 2;}
			if (x == 'left')  	{left -= width;}
		}

		return {top: top, left: left};
	}
	
	
	var validator = {
		needselect: function(v, nonvalue){
			if(v.value == nonvalue) {
				return false;
			}
			return true;
		},
		
		required: function(v){
			if(!v.value || !$.trim(v.value))
				return false;
			return true;
		},
		
		nonzero: function(v){
			if(v.value == 0) {
				return false;
			}
			return true;
		},

		integer: function(v){
			return this.regex(v, options.regex.integer);
		},

		number: function(v){
			return this.regex(v, options.regex.number);
		},

		digit: function(v){
			return this.regex(v, options.regex.digit);
		},

		decimal: function(v){
			return this.regex(v, options.regex.decimal);
		},

		alpha: function(v){
			return this.regex(v, options.regex.alpha);
		},

		alphanum: function(v){
			return this.regex(v, options.regex.alphanum);
		},

		equalto: function(v, elementId){
			return v.value == $('#' + elementId).val();
		},

		differs: function(v, elementId){
			return v.value != $('#' + elementId).val();
		},

		minlength: function(v, minlength, strip_tags){
			return (v.value.length >= parseInt(minlength));
		},

		maxlength: function(v, maxlength){
			return (v.value.length <= parseInt(maxlength));
		},

		rangelength: function(v, minlength, maxlength){		
			return (v.value.length >= parseInt(minlength) && v.value.length <= parseInt(maxlength));
		},

		min: function(v, min){
			if(v.selectedInGroup) {
				return v.selectedInGroup >= parseFloat(min);
			}
			else{
				if(!this.number(v)){
					return false;
				}
				return (parseFloat(v.value) >= parseFloat(min));
			}
		},

		max: function(v, max){		
			if(v.selectedInGroup){
				return v.selectedInGroup <= parseFloat(max);
			} else {
				if(!this.number(v)) {
					return false;
				}
				return (parseFloat(v.value) <= parseFloat(max));
			}
		},

		between: function(v, min, max){
			if(v.selectedInGroup)
				return (v.selectedInGroup >= parseFloat(min) && v.selectedInGroup <= parseFloat(max));
			if(!this.number(v))
				return false;
			var va = parseFloat(v.value);
			return (va >= parseFloat(min) && va <= parseFloat(max));
		},

		minInt: function(v, min){
			if(v.selectedInGroup) {
				return v.selectedInGroup >= parseInt(min);
			}
			else{
				if(!this.integer(v)){
					return false;
				}
				return (parseInt(v.value) >= parseInt(min))
			}
		},

		maxInt: function(v, max){		
			if(v.selectedInGroup){
				return v.selectedInGroup <= parseInt(max);
			} else {
				if(!this.integer(v)) {
					return false;
				}
				return (parseInt(v.value) <= parseInt(max));
			}
		},

		betweenInt: function(v, min, max){
			if(v.selectedInGroup)
				return (v.selectedInGroup >= parseInt(min) && v.selectedInGroup <= parseFloat(max));
			if(!this.integer(v))
				return false;
			var va = parseInt(v.value);
			return (va >= parseInt(min) && va <= parseInt(max));
		},

		Chinese: function(v){
			return this.regex(v, options.regex.Chinese);
		},

		idCard: function(v){
			return this.regex(v, options.regex.idCard);
		},

		telephone: function(v){
			return this.regex(v, options.regex.telephone);
		},

		mobile: function(v){
			return this.regex(v, options.regex.mobile);
		},

		zipcode: function(v){

			return this.regex(v, options.regex.zipcode);
		},

		qq: function(v){
			return this.regex(v, options.regex.qq);
		},

		email: function(v){
			return this.regex(v, options.regex.email);
		},

		url: function(v){
			return this.regex(v, options.regex.url);
		},

		date: function(v){
			return this.regex(v, options.regex.date);
		},

		time: function(v){
			return this.regex(v, options.regex.time);
		},

		dateTime: function(v){
			return this.regex(v, options.regex.dateTime);
		},

		ip4: function(v){
			var r = /^(([01]?\d\d?|2[0-4]\d|25[0-5])\.){3}([01]?\d\d?|25[0-5]|2[0-4]\d)$/;
			if (!r.test(v.value) || v.value == "0.0.0.0" || v.value == "255.255.255.255")
				return false;
			return true;
		},

		image: function(v){
			return this.regex(v, options.regex.image);
		},

		regex: function(v, regex, mod){
			if(typeof regex === "string")
				regex = new RegExp(regex, mod);
			return regex.test(v.value);
		},

		extension: function(){
			var v = arguments[0],
			 r = '';
			if(!arguments[1])
				return false;
			for(var i=1; i<arguments.length; i++){
				r += arguments[i];
				if(i != arguments.length-1)
					r += '|';
			}
			return this.regex(v, '\\.(' +  r  + ')$', 'i');
		}
	};
	
	
	validate = function(fieldId) {
		var ruleStr = rules[fieldId].rule;
		if (ruleStr.rule == '') {
			return true;
		}
		var _rule = ruleStr.split(options.ruleDelimiter);
		var rule = {};
		
		var inputValue = _getValue($('#'+fieldId));
		
		if (rules[fieldId].getValueFun) {
			inputValue.value = rules[fieldId].getValueFun();
		}
		
		// 无非空限制,则空输入返回合法
		if ($.inArray('required', _rule) == -1 && $('#'+fieldId).val()=='') {
			return true;
		}
		
		for (var ri in _rule) {
			
			var params = _rule[ri].match(/^(.*?)\[(.*?)\]/);
			if(params && params.length == 3){
				var validatorName = $.trim(params[1]);
				params = params[2].split(options.paramsDelimiter);
			}
			else{
				var validatorName = $.trim(_rule[ri]);
				params = [];
			}
			
			
			// if validator exists
			if(typeof validator[validatorName] == 'function'){
				params.unshift(inputValue); // add input value to beginning of params
				var validationResult = validator[validatorName].apply(validator, params); // call validator function
			}
			// call custom user dafined function
			else if(typeof window[validatorName] == 'function'){
				params.unshift(inputValue.value);
				var validationResult = window[validatorName].apply(validator, params);
			} else {
				return false;
			}
			
			if (!validationResult) {
				var errMsg = options.messages[validatorName];
				// replace values in braces
				if(errMsg.indexOf('{') != -1){
					for(var j=1; j<params.length; j++) {
						errMsg = errMsg.replace(new RegExp("\\{" + j + "\\}", "g"), params[j]);
					}
				}
				//errMsg = fieldId + ' ' + errMsg;
				errors[fieldId] = errMsg;
				_showErrMsg(fieldId, errMsg);
				return false;
			} else {
				// 去除之前的错误提示(如果存在)
				_removeErrMsg($('#'+fieldId));
			}
		};
		
		//validationResult = false;
		return validationResult;
	}
	
	$(this).submit(function(){
		if (extFunction) {
			var result = extFunction();
		} else {
			var result = true;
		}
		if (!result) {
			return false;
		}
		
		for (var fieldId in rules) {
			if (-1!=$.inArray(fieldId, nonExistsElement)) continue;
			ret = validate(fieldId);
			if (!ret) result = false;
		}

		if (result && options.ajax.use) {
			$.ajax({
				async		: false,
				type		: options.ajax.method,
				data		: $(this).serialize(),
				dataType	: options.ajax.dataType,
				url			: options.ajax.target,
				success		: function(data){options.ajax.callback(data);},
				error		: function (XMLHttpRequest, textStatus, errorThrown) {
								alert(errorThrown);
							}
			});
			result = false;
		}
		
		
		return result;
	});
	
};

	 
})(jQuery);
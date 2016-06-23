APP = {

	run: function(){
		APP.Main.init();
	},
	Libs: {

		_loadContent: function(source){
			if(!source.url && !source.title && source.directUrl){
				APP.Main._handleDirectUrl(source.directUrl);
			}else{
				$($('body').attr('data-crudizy-container')).load(source.url, function(){
					APP.Main._initializeFlashData();
				});

				$('title').html(source.title);
			}
		},
		_ajax: function(args){
			return $.ajax({
				type	: args.method,
				url		: args.url,
				async	: false,
				data	: args.data,
				cache	: false
			}).responseText;
		}

	},
	Main: {

		init: function(){
			this._initializeURL();
			this._pushState();
			this._backAndForward();
			this._initializeForm();
			this._crudizyParams();
		},
		_checkAndLoad: function(currRoute){
			if(typeof currRoute !== 'undefined'){
				APP.Libs._loadContent(currRoute);
			}else{
				console.log('You must assign to the ROUTES first!');
			}
		},
		_initializeURL: function(){
			var currUrl = document.URL.split('#/');
			var toLoad	= ((currUrl.length > 1) ? ROUTES[currUrl[1]] : ROUTES[ROUTES.DEFAULT]);

			this._checkAndLoad(toLoad);
		},
		_initializeForm: function(){
			$('body').on('submit', 'form[data-crudizy-form="enable"]', function(e){
				e.preventDefault();
				var data = JSON.parse(APP.Libs._ajax({
					method	: $(this).attr('method'),
					url 	: CONFIG.baseUrl + 'system/controllerHandler.php',
					data 	: $(this).serialize() + '&_action=' + $(this).attr('action')
				}));

				if(data['data-crudizy-feed'].message){
					localStorage.setItem(data['data-crudizy-feed'].message, true);
				}
				if(data['data-crudizy-feed'].redirect){
					window.location.href = CONFIG.baseUrl + '#/' + data['data-crudizy-feed'].redirect;
				}
			});
		},
		_initializeFlashData: function(){
			$('[data-crudizy-message]').hide();
			$('[data-crudizy-message]').each(function(){
				var curr = $(this).attr('data-crudizy-message');
				if(localStorage.getItem(curr)){
					$(this).show();
					localStorage.removeItem(curr);
				}
			});
		},
		_pushState: function(){
			var ob = this;
			$('body').on('click', 'a', function(e){
				var href = $(this).attr('href').split('/');
				ob._checkAndLoad(ROUTES[href[1]]);
			});
		},
		_backAndForward: function(){
			window.onpopstate = function(e){
				APP.Main._initializeURL();
			};
		},
		_crudizyParams: function(){
			$('body').on('click', '[data-crudizy-params]', function(e){
				e.preventDefault();
				var obj =  {};

				$(this).attr('data-crudizy-params').split(',').forEach(function(sp){
					var cur = sp.split(':');
					obj[cur[0]] = cur[1];
				});

				APP.Libs._ajax({
					method	: 'post',
					url 	: CONFIG.baseUrl + 'system/crudizyParamsControl.php',
					data 	: obj
				});

				window.location.href = CONFIG.baseUrl + $(this).attr('href');
			});
		},
		_handleDirectUrl: function(toUrl){
			var data = JSON.parse(APP.Libs._ajax({
				method	: 'post',
				url 	: CONFIG.baseUrl + 'system/controllerHandler.php',
				data 	: '_action=' + toUrl
			}));

			if(data['data-crudizy-feed'].message){
				localStorage.setItem(data['data-crudizy-feed'].message, true);
			}
			if(data['data-crudizy-feed'].redirect){
				window.location.href = CONFIG.baseUrl + '#/' + data['data-crudizy-feed'].redirect;
			}
		}

	}

};

$(APP.run);
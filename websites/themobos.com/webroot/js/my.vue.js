// add axios
Vue.prototype.$http = axios

//functions
var namegen = new Vue({
    delimiters: ['${', '}'],
    el: "#namegen",
    data: {
        names: []
    },
    methods : {
        gen : function ( event, name, limit) {
            var vm = this
			if( typeof limit == 'undefined' ) {
				limit = 20;
			}
            this.$http
                .get( '/g/'+name+'/'+limit )
                .then( function (response ) {
                    vm.names = response.data
                })
        }
    }
});

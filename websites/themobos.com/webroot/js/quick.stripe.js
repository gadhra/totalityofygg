var handler = StripeCheckout.configure({
    key: 'pk_test_xxxxx',
    image: '/path/to/128x128',
    locale: 'auto',
    token: function(token) {
        var $input = $('<input type=hidden name=stripeToken />').val(token.id);
        var $email = $('<input type=hidden name=email />' ).val( token. email );
        var $ip = $('<input type=hidden name=ipaddr />' ).val( token.client_id);
        $('form').append($input);
        $('form').append($email);
        $('form').append($ip);
        $('form').submit();
    }
});

$('button').on('click', function(e) {
    // Open Checkout with further options
    var amt = parseFloat( $( "input[name='amount']" ).val() * 100 );
    if( amt === 0 || isNaN(amt) ) {
        e.preventDefault();
        return;
    }
    
    handler.open({
        name: 'example.com',
        description: 'Quick and Dirty Example',
        amount: $( "input[name='amount']" ).val() * 100,
        currency: 'USD',
        panelLabel: 'Pay {{amount}}'
    });
    
    e.preventDefault();
});

// Close Checkout on page navigation
$(window).on('popstate', function() {
    handler.close();
});
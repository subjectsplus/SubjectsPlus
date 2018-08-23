<?php
/**
 * Created by PhpStorm.
 * User: pvillanueva
 * Date: 06/05/18
 */
?>

<script src="https://www.library.miami.edu/assets/js/modernizr.js"></script>
<script src="https://www.library.miami.edu/assets/js/js-offcanvas.pkgd.js"></script>

<script>
    // Append Around: https://github.com/filamentgroup/AppendAround

    (function( $ ){
        $.fn.appendAround = function(){
            return this.each(function(){
                var $self = $( this ),
                    att = "data-set",
                    $parent = $self.parent(),
                    parent = $parent[ 0 ],
                    attval = $parent.attr( att ),
                    $set = $( "["+ att +"='" + attval + "']" );
                function isHidden( elem ){
                    return $(elem).css( "display" ) === "none";
                }
                function appendToVisibleContainer(){
                    if( isHidden( parent ) ){
                        var found = 0;
                        $set.each(function(){
                            if( !isHidden( this ) && !found ){
                                $self.appendTo( this );
                                found++;
                                parent = this;
                            }
                        });
                    }
                }
                appendToVisibleContainer();
                $(window).bind( "resize", appendToVisibleContainer );
            });
        };
    }( jQuery ));

    // Off Canvas: https://github.com/vmitsaras/js-offcanvas/
    $( function(){
        $( document ).bind( "create.offcanvas", function( e ){
            $(e.target).removeClass('is-hidden');
        } );
        $( '#left' ).offcanvas( {
            modifiers: 'left,overlay',
            triggerButton: '.js-sidebar-toggler',
            resize: true
        } )
            .on( "resizing.offcanvas", function( e ){
                $('.nav-icon').removeClass('open-nav-icon');
            })
            .on( "close.offcanvas", function( e ){
                $('.nav-icon').removeClass('open-nav-icon');
            })
            .on( "open.offcanvas", function( e ){
                $('.nav-icon').addClass('open-nav-icon');
            });
        $( ".js-append-around" ).appendAround();
        $( document ).trigger( "enhance" );
    });

</script>
<script>
    // Account Dropdown
    $( function(){
        $('.nav-account a').click(function(event){
            event.stopPropagation();
            $('.nav-item.dropdown').removeClass('show');
            $('.nav-item.dropdown .dropdown-menu').removeClass('show');
            $('.account-dropdown').toggleClass('show');
            $('.nav-account a').toggleClass('active');
        });

        $(document).click( function(){
            $('.account-dropdown').removeClass('show');
            $('.nav-account a').removeClass('active');
        });

        $('.dropdown-toggle').click( function(){
            $('.account-dropdown').removeClass('show');
            $('.nav-account a').removeClass('active');
        });
    });

</script>
<script src="https://www.library.miami.edu/assets/webpack/common.bundle.js"></script>
<script src="https://www.library.miami.edu/assets/webpack/app.bundle.js"></script>
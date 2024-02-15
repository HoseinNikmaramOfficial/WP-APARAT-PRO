( function( blocks, element, components ) {
    var el = element.createElement;
    var TextControl = components.TextControl;

    blocks.registerBlockType( 'aparat/aparat-block', {
        title: 'Aparat Widget',
        icon: 'video-alt3',
        category: 'widgets',
        attributes: {
            username: {
                type: 'string',
                default: 'example'
            },
            videocount: {
                type: 'string',
                default: '1'
            },
            
        },

        edit: function( props ) {
            function onChangeUsername( newValue ) {
                props.setAttributes( { username: newValue } );
            }

            function onChangeVideoCount( newValue ) {
                props.setAttributes( { videocount: newValue } );
            }

            return el(
                'div',
                { className: props.className },
                el(
                    TextControl,
                    {
                        label: 'Aparat Username',
                        value: props.attributes.username,
                        onChange: onChangeUsername
                    }
                ),
                el(
                    TextControl,
                    {
                        label: 'Number of Videos',
                        value: props.attributes.videocount,
                        onChange: onChangeVideoCount
                    }
                )
            );
        },

        save: function( props ) {
            var username = props.attributes.username;
            var videocount = props.attributes.videocount;
            
            return '[aparat_block username="'+username+'" videocount="'+videocount+'"]';
        },
    } );
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.components
) );
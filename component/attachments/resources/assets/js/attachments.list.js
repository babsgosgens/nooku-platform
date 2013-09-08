if(!Attachments) var Attachments = {};

Attachments.List = new Class({
    element : null,

    initialize: function(options) {
        this.element = document.id(options.container);
        this.url = options.action;
        this.token = options.token;
        this.coordinates = '';

        if(!this.element) {
            return;
        }

        this.addCrop();

        var that = this;
        this.element.getElements('a[data-action]').each(function(a) {
            if(a.get('data-action'))
            {
                a.addEvent('click', function(e) {
                    e.stop();
                    that.execute(this.get('data-action'), this.get('data-id'), this.get('data-row'));
                });
            }
        });
    },

    addCrop: function()
    {
        var target = jQuery('#target');
        if (target.length) {
            target.Jcrop({
                aspectRatio: 4 / 3,
                minSize: [200, 150],
                setSelect: [10, 10, 210, 160],
                onSelect: this.setCoordinates.bind(this),
                onChange: this.setCoordinates.bind(this)
            });
        }
    },

    setCoordinates: function(c)
    {
        this.coordinates = c;
    },

    execute: function(action, id, row)
    {
        var method = '_action' + action.capitalize();

        if($type(this[method]) == 'function')
        {
            this.action = action;

            var uri = new URI(this.url);
            uri.setData('id', id);

            this[method].call(this, uri);
        }
    },

    _actionDelete: function(uri)
    {
        var form = new Koowa.Form({
            method: 'post',
            url: uri.toString(),
            params: {
                _action: 'delete',
                _token: this.token
            }
        });

        form.submit();
    },

    _actionCrop: function(uri)
    {
        jQuery.ajax({
            url: uri.toString(),
            dataType: 'json',
            method: 'post',
            data: {
                _action: 'edit',
                _token: this.token,
                x1: this.coordinates.x,
                y1: this.coordinates.y,
                x2: this.coordinates.x2,
                y2: this.coordinates.y2
            }
        }).then(function(data, textStatus, xhr) {
            if (xhr.status === 204) {
                jQuery.ajax({
                    url: uri.toString(),
                    dataType: 'json',
                    method: 'get'
                }).then(function(data, textStatus, xhr) {
                    if (xhr.status === 200 && typeof data.item.thumbnail === 'object') {
                        var thumbnail = data.item.thumbnail.thumbnail;
                        window.parent.jQuery('.thumbnail[data-id="'+data.item.id+'"] img').attr('src', thumbnail);

                        if (window.parent.SqueezeBox) {
                            window.parent.SqueezeBox.close();
                        }
                    }
                });
            } else {
                alert('Unable to crop thumbnail');
            }
        });
    }
});
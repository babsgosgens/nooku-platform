<?
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.mootools'); ?>
<?= @helper('behavior.keepalive'); ?>

<?= @helper('behavior.modal'); ?>

<style src="media://css/mootree.css" />
<style src="media://files/css/files.css" />
<script src="media://files/js/delegation.js" />
<script src="media://files/js/uri.js" />
<script src="media://files/js/history/history.js" />
<script src="media://files/js/history/history.html4.js" />

<script src="media://files/js/ejs/ejs.js" />

<script src="media://js/koowa.js" />
<script src="media://js/mootree.js" />
<script src="media://files/js/spin.min.js" />

<script src="media://files/js/files.utilities.js" />
<script src="media://files/js/files.state.js" />
<script src="media://files/js/files.template.js" />
<script src="media://files/js/files.grid.js" />
<script src="media://files/js/files.tree.js" />
<script src="media://files/js/files.row.js" />
<script src="media://files/js/files.paginator.js" />
<script src="media://files/js/files.pathway.js" />

<script src="media://files/js/files.app.js" />
<script>

if (SqueezeBox.open === undefined) {
	SqueezeBox = $extend(SqueezeBox, {
		open: function(subject, options) {
			this.initialize();

			if (this.element != null) this.close();
			this.element = document.id(subject) || false;

			this.setOptions($merge(this.presets, options || {}));

			if (this.element && this.options.parse) {
				var obj = this.element.getProperty(this.options.parse);
				if (obj && (obj = JSON.decode(obj, this.options.parseSecure))) this.setOptions(obj);
			}
			this.url = ((this.element) ? (this.element.get('href')) : subject) || this.options.url || '';

			this.assignOptions();

			var handler = handler || this.options.handler;
			if (handler) return this.setContent(handler, this.parsers[handler].call(this, true));
			var ret = false;
			return this.parsers.some(function(parser, key) {
				var content = parser.call(this);
				if (content) {
					ret = this.setContent(key, content);
					return true;
				}
				return false;
			}, this);
		}
	});
}

/* Joomla! 1.5 fix */
if(!SqueezeBox.handlers.clone) {
	SqueezeBox.handlers.adopt = function(a){return a;}
}
window.addEvent('domready', function(){
	if(!SqueezeBox.fx.win) {
		SqueezeBox.fx.win = SqueezeBox.fx.window;
	}
});
</script>
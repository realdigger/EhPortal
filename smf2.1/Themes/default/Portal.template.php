<?php
// Version: 1.1; Portal

function template_portal_above()
{
	global $context, $modSettings;

	if (empty($modSettings['sp_disable_side_collapse']) && ($context['SPortal']['sides'][1]['active'] || $context['SPortal']['sides'][4]['active']))
	{
		echo '
	<div class="sp_right sp_fullwidth">';

		if ($context['SPortal']['sides'][1]['active'])
			echo '
		<a href="#side" onclick="return sp_collapseSide(1)">', sp_embed_image($context['SPortal']['sides'][1]['collapsed'] ? 'expand' : 'collapse', '', null, null, true, 'sp_collapse_side1'), '</a>';

		if ($context['SPortal']['sides'][4]['active'])
			echo '
		<a href="#side" onclick="return sp_collapseSide(4)">', sp_embed_image($context['SPortal']['sides'][4]['collapsed'] ? 'expand' : 'collapse', '', null, null, true, 'sp_collapse_side4'), '</a>';

		echo '
	</div>';
	}

	if (!empty($context['SPortal']['blocks'][5]))
	{
		echo '
	<div id="sp_header">';

		foreach ($context['SPortal']['blocks'][5] as $block)
			template_block($block);

		echo '
	</div>';
	}

	echo '
	<table id="sp_main">
		<tr>';

	if (!empty($modSettings['showleft']) && !empty($context['SPortal']['blocks'][1]))
	{
		echo '
			<td id="sp_left"', $context['SPortal']['sides'][1]['collapsed'] && empty($modSettings['sp_disable_side_collapse']) ? ' style="display: none;"' : (!empty($modSettings['leftwidth']) ? ' style="width: ' . $modSettings['leftwidth'] . 'px;"' : ''), '>';

		foreach ($context['SPortal']['blocks'][1] as $block)
			template_block($block);

		echo '
			</td>';
	}

	echo '
			<td id="sp_center">';

	if (!empty($context['SPortal']['blocks'][2]))
	{
		foreach ($context['SPortal']['blocks'][2] as $block)
			template_block($block);

		if (empty($context['SPortal']['on_portal']))
			echo '
				<br class="sp_side_clear" />';
	}
}

function template_portal_below()
{
	global $context, $modSettings;

	if (!empty($context['SPortal']['blocks'][3]))
	{
		if (empty($context['SPortal']['on_portal']) || !empty($context['SPortal']['blocks'][2]) || !empty($modSettings['articleactive']))
			echo '
				<br class="sp_side_clear" />';

		foreach ($context['SPortal']['blocks'][3] as $block)
			template_block($block);
	}

	echo '
			</td>';

	if (!empty($modSettings['showright']) && !empty($context['SPortal']['blocks'][4]))
	{
		echo '
			<td id="sp_right"', $context['SPortal']['sides'][4]['collapsed'] && empty($modSettings['sp_disable_side_collapse']) ? ' style="display: none;"' : (!empty($modSettings['rightwidth']) ? ' style="width: ' . $modSettings['rightwidth'] . 'px;"' : ''), '>';

		foreach ($context['SPortal']['blocks'][4] as $block)
			template_block($block);

		echo '
			</td>';
	}
	echo '
		</tr>
	</table>';

	if (!empty($context['SPortal']['blocks'][6]))
	{
		echo '
	<div id="sp_footer">';

		foreach ($context['SPortal']['blocks'][6] as $block)
			template_block($block);

		echo '
	</div>
	<br />';
	}
}

function template_block($block)
{
	global $context, $modSettings, $txt;

	if (empty($block) || empty($block['type']))
		return;

	if ($block['type'] == 'sp_boardNews')
	{
		echo '
			<div class="sp_block_section', isset($context['SPortal']['sides'][$block['column']]['last']) && $context['SPortal']['sides'][$block['column']]['last'] == $block['id'] && ($block['column'] != 2 || empty($modSettings['articleactive'])) ? '_last' : '', '">';

		$block['type'](array_merge($block['parameters'], array('style' => $block['style'])), $block['id']);

		echo '
			</div>';

		return;
	}

	if (isset($txt['sp_custom_block_title_' . $block['id']]))
		$block['label'] = $txt['sp_custom_block_title_' . $block['id']];

	template_block_default($block);
}

function template_block_default($block)
{
	global $context, $modSettings, $settings;

	echo '
	<div class="sp_block_section', isset($context['SPortal']['sides'][$block['column']]['last']) && $context['SPortal']['sides'][$block['column']]['last'] == $block['id'] && ($block['column'] != 2 || empty($modSettings['articleactive'])) ? '_last' : '', '">';

	if (empty($block['style']['no_title']))
	{
		echo '
		<div class="', in_array($block['style']['title']['class'], array('titlebg', 'titlebg2')) ? 'title_bar' : 'cat_bar', '"', !empty($block['style']['title']['style']) ? ' style="' . $block['style']['title']['style'] . '"' : '', '>
			<h3 class="', $block['style']['title']['class'], '">';

		if (empty($block['force_view']))
			echo '
				<a class="sp_float_right_title" href="javascript:void(0);" onclick="sp_collapseBlock(\'', $block['id'], '\')"><span id="sp_collapse_', $block['id'], '" class="', ($block['collapsed'] ? 'toggle_down' : 'toggle_up'), '"></span></a>';

		echo '
				', parse_bbc($block['label']), '
			</h3>
		</div>';
	}

	echo '
		<div class="sp_block_title" id="sp_block_' . $block['id'] . '"', $block['collapsed'] && empty($block['force_view']) && empty($block['style']['no_title']) ? ' style="display: none;"' : '', '>';

	if (strpos($block['style']['body']['class'], 'roundframe') !== false)
	{
		echo '
			<span class="upperframe"><span></span></span>';
	}

	echo '
			<div', empty($block['style']['body']['class']) ? '' : ' class="' . $block['style']['body']['class'] . '"', '>';

	if (empty($block['style']['no_body']))
	{
		echo '
				<span class="topslice"><span></span></span>';
	}

	echo '
				<div class="', $block['type'] !== 'sp_menu' ? 'sp_block' : 'sp_content_padding', '"', !empty($block['style']['body']['style']) ? ' style="' . (!empty($block['calendar_style']) ? $block['calendar_style'] : '') . $block['style']['body']['style'] . '"' : ($block['type'] === 'sp_calendar' ? ' style="overflow: hidden;"' : ''), '>';

	$block['type']($block['parameters'], $block['id']);

	echo '
				</div>';

	if (empty($block['style']['no_body']))
	{
		echo '
				<span class="botslice"><span></span></span>';
	}

	echo '
			</div>';

	if (strpos($block['style']['body']['class'], 'roundframe') !== false)
	{
		echo '
			<span class="lowerframe"><span></span></span>';
	}

	echo '
		</div>
	</div>';
}

?>
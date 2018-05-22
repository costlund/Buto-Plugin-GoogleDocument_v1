<?php
/**
 * <p>Read a Google document inside an iframe. By using this widget one could mange them from one place and it will provide links and an iframe to run documents in.
 */
class PluginGoogleDocument_v1{
  /**
   * <p>Widget to render iframe on a page.
   */
  public static function widget_render($data){
    $language = wfI18n::getLanguage();
    $id = wfRequest::get('id');
    if(!$id){
      echo 'Param id is empty';
      return null;
    }
    wfPlugin::includeonce('wf/array');
    $data = new PluginWfArray($data);
    if(!$data->get("data/item/$id")){
      echo 'Could not find by id.';
      return null;
    }
    $style = null;
    if($data->get("data/item/$id/height")){
      $style = 'height:'.$data->get("data/item/$id/height").'px';
    }
    $element = array();
    $element[] = wfDocument::createHtmlElement('h1', $data->get("data/item/$id/name"));
    $element[] = wfDocument::createHtmlElement('iframe', null, array('src' => $data->get("data/item/$id/i18n/$language"), 'class' => 'document_iframe', 'style' => $style));
    wfDocument::renderElement($element);
  }
  /**
   * Widget to create links in a navbar. Href will point on a page where widget render is and this should have the same data settings.
   */
  public static function widget_navbar_links($data){
    wfPlugin::includeonce('wf/array');
    $data = new PluginWfArray($data);
    if($data->get('data/item')){
      $element = array();
      foreach ($data->get('data/item') as $key => $value) {
        $item = new PluginWfArray($value);
        $element[] = wfDocument::createHtmlElement('li', array(wfDocument::createHtmlElement('a', $item->get('name'), array('href' => $data->get('data/url').'/id/'.$key))));
      }
      wfDocument::renderElement($element);
    }
  }
  /**
   * Fetch a Google document and only keep html inside body tags.
   * Set data/file param for the url to Google document.
   */
  public static function widget_doc($data){
    wfPlugin::includeonce('wf/array');
    $data = new PluginWfArray($data);
    $html = file_get_contents($data->get('data/file'));
    $element = array();
    $html = strstr($html, '<body');
    $html = strstr($html, '>');
    $html = substr($html, 1);
    $html = strstr($html, '</body>', true);
    $element[] = wfDocument::createHtmlElement('div', $html);
    wfDocument::renderElement($element);
  }
}


























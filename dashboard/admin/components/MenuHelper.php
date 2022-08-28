<?php

namespace dashboard\admin\components;

use Yii;
use yii\caching\TagDependency;
use dashboard\admin\models\Menu;

/**
 * MenuHelper used to generate menu depend of user role.
 * Usage
 * 
 * ~~~
 * use dashboard\admin\components\MenuHelper;
 * use yii\bootstrap\Nav;
 *
 * echo Nav::widget([
 *    'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id)
 * ]);
 * ~~~
 * 
 * To reformat returned, provide callback to method.
 * 
 * ~~~
 * $callback = function ($menu) {
 *    $data = eval($menu['data']);
 *    return [
 *        'label' => $menu['name'],
 *        'url' => [$menu['route']],
 *        'options' => $data,
 *        'items' => $menu['children']
 *        ]
 *    ]
 * }
 *
 * $items = MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback);
 * ~~~
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class MenuHelper
{
    const CACHE_TAG = 'mdm.admin.menu';

    /**
     * Use to get assigned menu of user.
     * @param mixed $userId
     * @param integer $root
     * @param \Closure $callback use to reformat output.
     * callback should have format like
     * 
     * ~~~
     * function ($menu) {
     *    return [
     *        'label' => $menu['name'],
     *        'url' => [$menu['route']],
     *        'options' => $data,
     *        'items' => $menu['children']
     *        ]
     *    ]
     * }
     * ~~~
     * @param boolean  $refresh
     * @return array
     */
    public static function getAssignedMenu($userId, $root = null, $callback = null, $refresh = false)
    {
        $config = Configs::instance();
        
        /* @var $manager \yii\rbac\BaseManager */
        $manager = Yii::$app->getAuthManager();
        $menus = Menu::find()->asArray()->indexBy('id')->all();
        $key = [__METHOD__, $userId, $manager->defaultRoles];
        $cache = $config->cache;

        if ($refresh || $cache === null || ($assigned = $cache->get($key)) === false) {
            $routes = $filter1 = $filter2 = [];
            if ($userId !== null) {
                foreach ($manager->getPermissionsByUser($userId) as $name => $value) {
                    if ($name[0] === '/') {
                        if (substr($name, -2) === '/*') {
                            $name = substr($name, 0, -1);
                        }
                        $routes[] = $name;
                    }
                }
            }
            foreach ($manager->defaultRoles as $role) {
                foreach ($manager->getPermissionsByRole($role) as $name => $value) {
                    if ($name[0] === '/') {
                        if (substr($name, -2) === '/*') {
                            $name = substr($name, 0, -1);
                        }
                        $routes[] = $name;
                    }
                }
            }
            $routes = array_unique($routes);
            sort($routes);
            $prefix = '\\';
            foreach ($routes as $route) {
                if (strpos($route, $prefix) !== 0) {
                    if (substr($route, -1) === '/') {
                        $prefix = $route;
                        $filter1[] = $route . '%';
                    } else {
                        $filter2[] = $route;
                    }
                }
            }
            $assigned = [];
            $query = Menu::find()->select(['id'])->asArray();
            if (count($filter2)) {
                $assigned = $query->where(['route' => $filter2])->column();
            }
            if (count($filter1)) {
                $query->where('route like :filter');
                foreach ($filter1 as $filter) {
                    $assigned = array_merge($assigned, $query->params([':filter' => $filter])->column());
                }
            }
            $assigned = static::requiredParent($assigned, $menus);
            if ($cache !== null) {
                $cache->set($key, $assigned, $config->cacheDuration, new TagDependency([
                    'tags' => self::CACHE_TAG
                ]));
            }
        }

        $key = [__METHOD__, $assigned, $root];
        if ($refresh || $callback !== null || $cache === null || (($result = $cache->get($key)) === false)) {
            $result = static::normalizeMenu($assigned, $menus, $callback, $root);
            if ($cache !== null && $callback === null) {
                $cache->set($key, $result, $config->cacheDuration, new TagDependency([
                    'tags' => self::CACHE_TAG
                ]));
            }
        }

        return $result;
    }

    /**
     * Ensure all item menu has parent.
     * @param  array $assigned
     * @param  array $menus
     * @return array
     */
    private static function requiredParent($assigned, &$menus)
    {
        $l = count($assigned);
        for ($i = 0; $i < $l; $i++) {
            $id = $assigned[$i];
            $parent_id = $menus[$id]['parent'];
            if ($parent_id !== null && !in_array($parent_id, $assigned)) {
                $assigned[$l++] = $parent_id;
            }
        }

        return $assigned;
    }

    /**
     * Parse route
     * @param  string $route
     * @return mixed
     */
    public static function parseRoute($route)
    {
        if (!empty($route)) {
            $url = [];
            $r = explode('&', $route);
            $url[0] = $r[0];
            unset($r[0]);
            foreach ($r as $part) {
                $part = explode('=', $part);
                $url[$part[0]] = isset($part[1]) ? $part[1] : '';
            }

            return $url;
        }

        return '#';
    }

    /**
     * Normalize menu
     * @param  array $assigned
     * @param  array $menus
     * @param  Closure $callback
     * @param  integer $parent
     * @return array
     */
    private static function normalizeMenu(&$assigned, &$menus, $callback, $parent = null)
    {
        $result = [];
        $order = [];
        foreach ($assigned as $id) {
            $menu = $menus[$id];
            if ($menu['parent'] == $parent) {
                $menu['children'] = static::normalizeMenu($assigned, $menus, $callback, $id);
                if ($callback !== null) {
                    $item = call_user_func($callback, $menu);
                } else {
                    $item = [
                        'label' => $menu['name'],
                        'url' => static::parseRoute($menu['route']),
                    ];
                    if ($menu['children'] != []) {
                        $item['items'] = $menu['children'];
                    }
                }
                $result[] = $item;
                $order[] = $menu['order'];
            }
        }
        if ($result != []) {
            array_multisort($order, $result);
        }

        return $result;
    }

    /**
     * Use to invalidate cache.
     */
    public static function invalidate()
    {
        if (Configs::instance()->cache !== null) {
            TagDependency::invalidate(Configs::instance()->cache, self::CACHE_TAG);
        }
    }
    
    
    //----------------------------------------------------
    
    
    public static function getAssignedMenuCustom($userId)
    {
        $manager = Yii::$app->getAuthManager();
        $assigned = $manager->getPermissionsByUserCustom($userId);
        
        if(!empty($assigned))
        {
            $menuIds = [];
            foreach ($assigned as $name => $value) {
                if(!empty($value->data)){
                    $data = json_decode($value->data, true);
                    $ids = array_keys($data['menu'], 1);
                    foreach ($ids as $value) {
                        $menuIds[] = $value;
                    }
                }else{
                    unset($assigned[$name]);
                }
            }
            
            if(!empty($menuIds)){
                $menuIds = array_unique($menuIds);
                $menus = Menu::find()->select('id, name AS label, parent, route, order, data')->where(['in', 'id', $menuIds])->asArray()->all();
                $assigned = static::menuTreeAssign($menus);
            }
        }
        
        return $assigned;
    }
    
    public static function menuTreeAssign($elements) 
    {
        $res = [];
        $menus = Menu::find()->select('id, name AS label, parent, route, order, data')->asArray()->indexBy('id')->all();
        
        foreach($elements as $val)
        {
            if($val['parent'] != 1 && isset($val['parent']))
            {
                $res[$val['parent']] = empty($res[$val['parent']]) ? $menus[$val['parent']] : $res[$val['parent']];
                $res[$val['parent']]['items'][] = $val;
                $order = array_column($res[$val['parent']]['items'], 'order');
                array_multisort($order, SORT_ASC, $res[$val['parent']]['items']);
            } else 
            {
                $res[$val['id']] = $val;
            }
        }
        
        foreach ($res as $key => $value) {
            if($value['parent'] != 1){
                if(isset($res[$value['parent']])){
                    foreach ($res as $key2 => $value2) {
                        if($value2['parent'] == $key){
                            $value['items'][] = $value2;
                            unset($res[$key2]);
                        }
                    }
                    $res[$value['parent']]['items'][] = $value;
                    $order = array_column($res[$value['parent']]['items'], 'order');
                    array_multisort($order, SORT_ASC, $res[$value['parent']]['items']);
                    unset($res[$key]);
                }
            }
        }
        
//        $res = static::normilizeTree($res, $menus);
        
        $order = array_column($res, 'order');
        array_multisort($order, SORT_ASC, $res);
        
        return $res;
    }
    
    public static function normilizeTree($elements, $menus, $menu = [])
    {
        foreach ($elements as $key => $value){
            if($value['parent'] != 1){
                $parent = $menus[$value['parent']];
                $parent['items'][] = $elements[$key];
                unset($elements[$key]);
                $elements[$value['parent']] = $parent;
            }else{
                $parent = $menus[$key];
                $items = isset($value['items']) ? $value['items'] : [];
                $parent['items'] = $items;
                $menu[] = $parent;
                unset($elements[$key]);
            }
        }
        
        if(!empty($elements)){
            $menu = static::normilizeTree($elements, $menus, $menu);            
        }
        
        return $menu;
    }

    public static function menuTree(array &$elements, $parentId = 1) 
    {
        $branch = array();


        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = static::menuTree($elements, $element['id']);
                if ($children) {
                    $order = array_column($children, 'order');
                    array_multisort($order, SORT_ASC, $children);
                    $element['children'] = $children;
                }
                
                $element['access'] = 0;
                $branch[$element['id']] = $element;
//                unset($elements[$element['id']]);
            }
        }
        
        $order = array_column($branch, 'order');
        array_multisort($order, SORT_ASC, $branch);
        
        return $branch;
    }
    
    public static function menuHtml($data, $form, $model) 
    {
        $result = array();
        if (sizeof($data) > 0) {
            $result[] = '<ol>';
            foreach ($data as $entry) {
                if(!empty($entry['children'])){
                    $result[] = sprintf('<li><a>%s</a> %s</li>', $entry['name'], static::menuHtml($entry['children'], $form, $model));
                }else{
                    $background = (!empty($model->data) && isset($model->data['menu'][$entry['id']]) && $model->data['menu'][$entry['id']])?"checked-background":"";
                    $result[] = sprintf('<li><a class="cursor '.$background.'">%s %s</a></li>', $entry['name'], $form->field($model, 'data[menu]['.$entry['id'].']')->checkBox(['label'=>false, 'maxlength' => true]));
                }
            }
            $result[] = '</ol>';
        }

        return implode($result);
    }
    
    public static function renderMenu($data, $cssClass = 'nav side-menu')
    {
        $result = array();
        if (sizeof($data) > 0) {
            $result[] = '<ul class="'.$cssClass.'">';
            foreach ($data as $entry) {
                if(!empty($entry['items'])){
                    $icon = '';
                    if(!empty($entry['data'])){
                        $icon = json_decode($entry['data'], true);
                        $icon = isset($icon['icon'])? '<i class="'.$icon['icon'].'"></i>' : '';
                    }
                    $result[] = sprintf('<li class=""><a href="javascript:void(0);">'.$icon.' %s <span class="fa fa-chevron-down"></span></a> %s </li>', $entry['label'], static::renderMenu($entry['items'], 'nav child_menu'));
                }else{
                    $urlAttr = '';
                    $icon = '';
                    if(!empty($entry['data'])){
                        $attr = json_decode($entry['data'], true);
                        $urlAttr = isset($attr['routeAttribute']) ? '/'.$attr['routeAttribute'] : '';
                        $icon = isset($attr['icon'])? '<i class="'.$attr['icon'].'"></i>' : '';
                    }

                    $url = \yii\helpers\Url::to(['/'.$entry['route'].$urlAttr]);
                    $result[] = sprintf('<li><a href="'.$url.'" class="cursor">'.$icon.' %s</a></li>', $entry['label']);
                }
            }
            $result[] = '</ul>';
        }
        
        return implode($result);
    }
}

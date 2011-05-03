<?php
/**
 * Update a User
 * 
 * @package discuss
 */
/* get user */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('discuss.user_err_ns'));
$c = $modx->newQuery('disUser');
$c->innerJoin('modUser','User');
$c->where(array(
    'id' => $scriptProperties['id'],
));
$user = $modx->getObject('disUser',$c);
if (!$user) return $modx->error->failure($modx->lexicon('discuss.user_err_nf',array('id' => $scriptProperties['id'])));

/* set fields */
unset($scriptProperties['id']);
$scriptProperties['show_email'] = !empty($scriptProperties['show_email']) ? true : false;
$scriptProperties['show_online'] = !empty($scriptProperties['show_online']) ? true : false;
$user->fromArray($scriptProperties);

/* save user */
if (!$user->save()) {
    return $modx->error->failure($modx->lexicon('discuss.user_err_save'));
}

/* save username if changed */
$modxUser = $user->getOne('User');
if ($modxUser) {
    $modxUser->fromArray($scriptProperties);
    $modxUser->save();
}

$ra = $user->toArray();
unset($ra['password'],$ra['cachepwd']);
return $modx->error->success('',$ra);
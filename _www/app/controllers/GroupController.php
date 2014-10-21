<?php

/**
* CREATE - READ - UPDATE - DELETE
*/

class GroupController extends AuthController {


    /**
     * allPaginated
     * retrieve all groups that match the filters provided
     *
     * ACL
     * get method arguments
     * perform a checking on param type
     * instantiate a new group object
     * map to this object the filters and the options
     * set the framework's view variables
     * instantiate a pager object and its options
     *
     * @return mixed    view of the paginated group result
     */
    public function allPaginated()
    {
        if( $this->f3->get('SESSION.lvl') <= 3 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');
            $filters = $options = array();
            $filterQuery = '1 = ?';
            $filterValue = null;
            if( empty($params->filter) )
            {
                $options['order'] = 'name ASC';
            }
            else
            {
                $options['order'] = empty($params->option) ? $params->filter.' ASC' : $params->filter.' '.$params->optionvalue;
                if( !empty($params->filtervalue) )
                {
                    $filterQuery .= ' AND '.$params->filter.' LIKE ?';
                    $filterValue .= '%'.$params->filtervalue.'%';
                }
            }
            $filters[] = $filterQuery;
            $filters[] = '1';
            if( !empty($filterValue) ) {
                $filters[] = $filterValue;
            }
            $groups = new viewGroupUsers($this->db);
            $group_list = $groups->getGroupListFiltered_Paginated($filters, $options);
            $this->f3->mset(
                array(
                    'lists_keys' => (isset($group_list['subset'][0]) ? array_keys($group_list['subset'][0]) : null),
                    'lists' => $group_list['subset'],
                    'totaux' => (isset($group_list['total']) && $group_list['total']>0 ? $group_list['total'] : 0),
                    'listtype' => 'groups',
                    'listindex' => 'gid',
                    'page_header' => ucfirst($this->T('group_list')),
                    'listname' => $this->T('groups'),
                    'filter' => $filter,
                    'filtervalue' => $filtervalue,
                    'view' => 'group/list.htm'
                )
            );
            $pages = new Pagination($group_list['total'], $group_list['limit']);
            $pages->setRouteKeyPrefix('page/');
            $pages->setTemplate('pagination.htm');
            $this->f3->set('pagebrowser', $pages->serve());
        } else {
            $this->f3->reroute('/');
        }
    }


    /**
     * getContacts
     *
     * ACL
     * instantiate a group object,
     * a user group object
     * and business infos for each user in a group
     *
     * @return mixed    view of a group and its users infos
     */
    public function getContacts()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            $params = array_map('trim', $this->f3->get('PARAMS'));
            $group_users = new viewGroupUsers($this->db);
            $group_stats = $group_users->getGroupStatByGid_Raw($params['gid']);
            $count_users = $group_stats[0]['nbUsers'];
            $arr_users = explode(',', $group_stats[0]['uids']);
            $profiles = new viewUserCompleteProfile($this->db);
            $users_in_group = $count_users > 0 ? $profiles->getUsersMinimumProfileByUidsIn_Raw($arr_users) : 0;
            $this->f3->mset(
                array(
                    'group_users' => array(
                        $group_stats[0],
                        $users_in_group
                    ),
                    'page_header' => ucfirst($this->T('group')),
                    'view' => 'group/show.htm'
                )
            );
        } else {
            $this->f3->reroute('/');
        }
    }


    /**
     * create
     *
     * ACL
     * clean form errors variable
     * if post (a form has been submitted)
     * perform a validation on each field value
     * set error messages if exist
     * if no error, perform an add method on object group
     * set success message
     * reroute to groups home page
     * if errors, collect error messages
     * set error variables
     * return to form view
     *
     * @return mixed    list view of all groups with the newly added one
     */
    public function create()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            if($this->f3->exists('POST.create'))
            {
                $post = array_map('trim', $this->f3->get('POST'));
                $check = new Test(1);
                $errors = array();
                foreach($post as $post_index => $post_value)
                {
                    $t_text = 'group_'.$post_index.'_required';
                    $check->expect(strlen($post_value)<2, $t_text);
                }
                $r = $check->results();
                if(count($r)==0)
                {
                    $group = new Groups($this->db);
                    $group->add();
                    $this->f3->set('ESCAPE', FALSE);
                    $msg = ucfirst($this->T('group')).' <b>'.$post['name'].'</b> '.$this->T('created').' !';
                    $this->setMessage($msg);
                    $this->f3->reroute('/groups');
                }
                else
                {
                    while (list(, $value) = each($r))
                    {
                        list($e, $i, $x) = explode('_', $value['text']);
                        $this->errors($this->T($value['text']));
                    }
                    $this->f3->set('post_has_data', $post);
                    $this->f3->set('view', 'group/form/create.htm');
                }
            }
            else
            {
                $this->f3->set('post_has_data', '');
                $this->f3->set('view', 'group/form/create.htm');
            }
        }
        else
        {
            $this->f3->reroute('/groups');
        }
    }


    /**
     * update
     * update a group name
     */
    public function update() {
        if( $this->f3->get('SESSION.lvl') <= 2 ) {
            $group = new Groups($this->db);
            if($this->f3->exists('POST.update')) {
                $group->edit($this->f3->get('POST.gid'));
                $this->f3->set('ESCAPE', FALSE);
                $msg = ucfirst($this->T('group')).' <b>'.$this->f3->get('POST.name').'</b> '.$this->T('updated').' !';
                $this->setMessage($msg);
                $this->f3->reroute('/groups');
            } else {
                $u = $group->load(array('gid=?', $this->f3->get('PARAMS.gid')));
                $this->f3->set('group', $u);
                $this->f3->set('view','group/form/update.htm');
            }
        } else {
            $this->f3->reroute('/groups');
        }
    }


    /**
     * delete
     * delete a user group
     * only the relation is destroyed,
     * the users infos remains untouched
     *
     * instantiate a group object
     * hydrate the object with post datas
     * invoke the delete method of the mapper object
     * set a success message
     * reroute to groups home page
     */
    public function delete() {
        if( $this->f3->get('SESSION.lvl') <= 2 ) {
            $group = new Groups($this->db);
            if($this->f3->exists('POST.del')) {
                $group->delete($this->f3->get('POST.gid'));
                $msg = ucfirst($this->T('group')).' '.$this->T('deleted').' !';
                $msg = '<b>'.ucfirst($this->f3->get('POST.groupname')).'</b> '.$this->T('deleted');
                $this->setMessage($msg);
            }
        }
        $this->f3->reroute('/groups');
    }


    /**
     * getContactsOutofGroup
     * retrieve all users not listed inside the current group
     *
     * retrieve the group with its id
     * retrieve a usergroup and return the users in this group
     * retrieve infos of the users returned by usergroup search
     * set params for rendering
     * instantiate a pager object with user list
     */
    public function getContactsOutofGroup()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            $params = (object) array_map('trim', $this->f3->get('PARAMS'));
            $filter = (isset($params->filter) ? $params->filter : '');
            $filtervalue = (isset($params->filtervalue) ? $params->filtervalue : '');
            $group = new viewGroupUsers($this->db);
            $group_infos = $group->getGroupStatByGid_Raw($params->gid)[0];
            $filters = $options = array();
            $filterQuery = 'uid '.($group_infos['nbUsers'] > 0 ? 'NOT IN ('.$group_infos['uids'].')' : 'IS NOT NULL');
            $filterValue = '';
            if( empty($params->filter) )
            {
                $options['order'] = 'nom ASC';
            }
            else
            {
                $options['order'] = empty($params->option) ? $params->filter.' ASC' : $params->filter.' '.$params->optionvalue;
                if( !empty($params->filtervalue) )
                {
                    $this->f3->set('filter', $params->filter);
                    $this->f3->set('filtervalue', $params->filtervalue);
                    $filterQuery .= ' and '.$params->filter.' LIKE ?';
                    $filterValue .= '%'.$params->filtervalue.'%';
                }
            }
            $filters[] = $filterQuery." AND nom != ''";
            if( !empty($filterValue) )
            {
                $filters[] = $filterValue;
            }
            $profiles = new viewUserCompleteProfile($this->db);
            $users_listed = $group_infos['nbUsers'] > 0 ?
                $profiles->getUsersProfilesWithUidNOTInListFiltered_Paginated($filters, $options) :
                $profiles->getUsersProfilesFiltered_Paginated($filters, $options);

            $this->f3->mset(
                array(
                    'group' => $group_infos,
                    'lists_keys' => (isset($users_listed['subset'][0]) ? array_keys($users_listed['subset'][0]) : null),
                    'lists' => $users_listed['subset'],
                    'totaux' => $users_listed['total'],
                    'listtype' => 'group',
                    'listindex' => 'gid',
                    'filter' => $filter,
                    'filtervalue' => $filtervalue,
                    'page_header' => ucfirst($this->T('group')),
                    'listname' => $users_listed['total']>1 ? $this->T('contacts') : $this->T('contact'),
                    'search_header' => $this->T('search_user'),
                    'search_pat' => "/group/$params->gid/add/user/nom/___/order/asc",
                    'no_search_pat' => "/group/$params->gid/add/user",
                    'view' => 'group/addcontact.htm'
                )
            );
            $pages = new Pagination($users_listed['total'], $users_listed['limit']);
            $pages->setRouteKeyPrefix('page/');
            $pages->setTemplate('pagination.htm');
            $this->f3->set('pagebrowser', $pages->serve());
        }
        else
        {
            $this->f3->reroute('/groups');
        }
    }


    /**
     * addContact
     * add a contact to a group
     *
     * Instantiate a usergroup
     * add the form datas to the hydrated object
     * set a success message
     * return to the current group
     */
    public function addContact()
    {
        if( $this->f3->get('SESSION.lvl') <= 2 )
        {
            if($this->f3->exists('POST.addtogroup'))
            {
                $group_user = new GroupUsers($this->db);
                $group_user->add();
                $msg = '<b>'.ucfirst($this->f3->get('POST.username')).'</b> '.$this->T('added').' '.$this->T('togroup').' <b>'.$this->f3->get('POST.groupname').'</b>';
                $this->setMessage($msg);
            }
        }
        $this->f3->reroute('/group/'.$this->f3->get('POST.groupID').'/show');
    }


    /**
     * removeContact
     * delete a usergroup provided a group id and a uid
     * instead of using the record id which can differ from one machine to another...
     *
     * retrieve a usergroup
     * perform a delete
     * set a deletion success message
     * reroute to the group page
     */
    public function removeContact() {
        if( $this->f3->get('SESSION.lvl') <= 2 ) {
            $groupuser = new GroupUsers($this->db);
            $groupuser->delete(array($this->f3->get('PARAMS.gid'), $this->f3->get('PARAMS.uid')));
            $msg = '<b>'.ucfirst($this->f3->get('POST.username')).'</b> '.$this->T('removed').' '.$this->T('from_group').' <b>'.$this->f3->get('POST.groupname').'</b>';
            $this->setMessage($msg);
        }
        $this->f3->reroute('/group/'.$this->f3->get('PARAMS.gid').'/show');
    }


}


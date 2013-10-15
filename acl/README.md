ACL allow to handle permissions in your application : <http://en.wikipedia.org/wiki/Access_control_list>


### Configuration

> config/aclGroups.yml

```
Acl:
- AclGroup
- Acl
- SuperAdmin
```

You can defines your groups and your permissions in this file. The group name is used by the user group's permissions management controller 

### Use

Add an url in your layout menu (or anywhere you want)

> viewLayouts/page.php


			'Acl':array('/acl','visible'=>ACAcl::checkAccess('Acl')),

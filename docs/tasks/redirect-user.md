Task: redirect User to a custom URL
===============================================

This tasks make use of the redirect function of the Workflow Designer.

This means if you create these task, you could define a custom URL, which could also be build by values from the current Record. You could use all default template features.

The executing User will be redirected to these URL directly after the Workflow is completed.

**CAUTION! This means no more Workflow will be executed after the current one!  
Also possible other modules will be “kicked” if you use this function. The current structure of vtigerCRM don’t allow another way to integrate this feature.**
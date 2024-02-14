CKEDITOR.editorConfig = function( config )
{
    // Add[MT]to the integration list
    config.extraPlugins += (config.extraPlugins.length == 0 ? '' : ',') + 'ckeditor_wiris';
    config.allowedContent = true;
    config.removePlugins = 'easyimage, cloudservices';
    // toolbar_Full = [];
    // config.toolbar_Full.push({ name: 'wiris', items : [ 'ckeditor_wiris_formulaEditor','ckeditor_wiris_formulaEditorChemistry']});
};
//If you use the Full MathML mode and for CKeditor versions higher than 4.0 you have to add this line as well:
 

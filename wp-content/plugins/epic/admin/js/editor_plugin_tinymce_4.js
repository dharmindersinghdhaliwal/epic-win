(function () {
	"use strict";

	var epicShortcodesManager = function(editor, url) {

		editor.addButton('epic_shortcodes_button', function() {
            var icon = url + '/../images/epic-icon-20.png';

			return {
				title: '',
				image: icon,
				type: 'menubutton',
                icon : 'epic_shortcodes_button',
				menu: [
					{
						text: epicTmce.LoginRegistrationForms,
						menu: [
							{
								text: epicTmce.FrontRegistrationForm,
								onclick: function(){
									editor.insertContent('[epic_registration]');
								}
							},
							{
								text: epicTmce.RegFormCustomRedirect,
								onclick: function(){
									editor.insertContent('[epic_registration redirect_to="http://url_here"]');
								}
							},
							{
								text: epicTmce.RegFormCaptcha,
								onclick: function(){
									editor.insertContent('[epic_registration captcha=yes]');
								}
							},
							{
								text: epicTmce.RegFormNoCaptcha,
								onclick: function(){
									editor.insertContent('[epic_registration captcha=no]');
								}
							},
							{
								text: epicTmce.FrontLoginForm,
								onclick: function(){
									editor.insertContent('[epic_login]');
								}
							},
							{
								text: epicTmce.SidebarLoginWidget,
								onclick: function(){
									editor.insertContent('[epic_login use_in_sidebar=yes]');
								}
							},
							{
								text: epicTmce.LoginFormCustomRedirect,
								onclick: function(){
									editor.insertContent('[epic_login redirect_to="http://url_here"]');
								}
							},
							{
								text: epicTmce.LogoutButton,
								onclick: function(){
									editor.insertContent('[epic_logout]');
								}
							},
							{
								text: epicTmce.LogoutButtonCustomRedirect,
								onclick: function(){
									editor.insertContent('[epic_logout redirect_to="http://url_here"]');
								}
							},
						]
					},
					{
						text: epicTmce.SingleProfile,
						menu: [
							{
								text: epicTmce.LoggedUserProfile,
								onclick: function(){
									editor.insertContent('[epic]');
								}
							},
							{
								text: epicTmce.LoggedUserProfileUserID,
								onclick: function(){
									editor.insertContent('[epic show_id=true]');
								}
							},
							{
								text: epicTmce.PostAuthorProfile,
								onclick: function(){
									editor.insertContent('[epic id=author]');
								}
							},
							{
								text: epicTmce.SpecificUserProfile,
								onclick: function(){
									editor.insertContent('[epic id=X]');
								}
							},
							{
								text: epicTmce.LoggedUserProfileHideStats,
								onclick: function(){
									editor.insertContent('[epic show_stats=no]');
								}
							},
							{
								text: epicTmce.LoggedUserProfileUserRole,
								onclick: function(){
									editor.insertContent('[epic show_role=yes]');
								}
							},
							{
								text: epicTmce.LoggedUserProfileStatus,
								onclick: function(){
									editor.insertContent('[epic show_profile_status=yes]');
								}
							},
							{
								text: epicTmce.LoggedUserProfileLogoutRedirect,
								onclick: function(){
									editor.insertContent('[epic logout_redirect=http://url_here]');
								}
							},
						]
					},
					{
						text: epicTmce.MultipleProfilesMemberList,
						menu: [
							{
								text: epicTmce.GroupSpecificUsers,
								onclick: function(){
									editor.insertContent('[epic group=user_id1,user_id2,user_id3,etc]');
								}
							},
							{
								text: epicTmce.AllUsers,
								onclick: function(){
									editor.insertContent('[epic group=all users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersCompactView,
								onclick: function(){
									editor.insertContent('[epic group=all view=compact users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersCompactViewHalfWidth,
								onclick: function(){
									editor.insertContent('[epic group=all view=compact width=2 users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersModalWindow,
								onclick: function(){
									editor.insertContent('[epic group=all view=compact modal=yes users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersNewWindow,
								onclick: function(){
									editor.insertContent('[epic group=all view=compact new_window=yes users_per_page=10]');
								}
							},
							{
								text: epicTmce.UsersBasedUserRole,
								onclick: function(){
									editor.insertContent('[epic group=all role=subscriber users_per_page=10]');
								}
							},
							{
								text: epicTmce.AdministratorUsersOnly,
								onclick: function(){
									editor.insertContent('[epic group=all role=administrator users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersOrderedDisplayName,
								onclick: function(){
									editor.insertContent('[epic group=all order=asc orderby=display_name users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersOrderedPostCount,
								onclick: function(){
									editor.insertContent('[epic group=all order=desc orderby=post_count users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersOrderedRegistrationDate,
								onclick: function(){
									editor.insertContent('[epic group=all order=desc orderby=registered users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersOrderedCustomField,
								onclick: function(){
									editor.insertContent('[epic group=all order=desc orderby=custom_field_meta_key orderby_custom=yes users_per_page=10]');
								}
							},
							{
								text: epicTmce.AllUsersUserID,
								onclick: function(){
									editor.insertContent('[epic group=all show_id=true users_per_page=10]');
								}
							},
							{
								text: epicTmce.GroupUsersCustomField,
								onclick: function(){
									editor.insertContent('[epic group=all  group_meta=custom_field_key group_meta_value=custom_field_value users_per_page=10]');
								}
							},
							{
								text: epicTmce.HideUsersUntilSearch,
								onclick: function(){
									editor.insertContent('[epic group=all users_per_page=10 hide_until_search=true]');
								}
							},
							{
								text: epicTmce.SearchProfile,
								onclick: function(){
									editor.insertContent('[epic_search operator=OR]');
								}
							},
							{
								text: epicTmce.SearchCustomFieldFilters,
								onclick: function(){
									editor.insertContent('[epic_search filters=meta1,meta2,meta3]');
								}
							},

						]
					},
					{
						text: epicTmce.PrivateContentLoginRequired,
						menu: [
							{
								text: epicTmce.PrivateContentLoginRequired,
								onclick: function(){
									editor.insertContent('[epic_private]Place member only content here[/epic_private]');
								}
							},

						]
					},
					{
						text: epicTmce.ShortcodeOptionExamples,
						menu: [
							{
								text: epicTmce.HideUserStatistics,
								onclick: function(){
									editor.insertContent('[epic show_stats=no]');
								}
							},
							{
								text: epicTmce.HideUserSocialBar,
								onclick: function(){
									editor.insertContent('[epic show_social_bar=no]');
								}
							},
							{
								text: epicTmce.HalfWidthProfileView,
								onclick: function(){
									editor.insertContent('[epic width=2]');
								}
							},
							{
								text: epicTmce.CompactViewNoExtraFields,
								onclick: function(){
									editor.insertContent('[epic view=compact]');
								}
							},
							{
								text: epicTmce.CustomizedProfileFields,
								onclick: function(){
									editor.insertContent('[epic view=meta_id1,meta_id2,meta_id3]');
								}
							},
							{
								text: epicTmce.ShowUserIDProfiles,
								onclick: function(){
									editor.insertContent('[epic show_id=true]');
								}
							},
							{
								text: epicTmce.LimitResultsMemberList,
								onclick: function(){
									editor.insertContent('[epic group=all view=compact limit_results=yes users_per_page=4 ]');
								}
							},
							{
								text: epicTmce.ShowResultCountMemberList,
								onclick: function(){
									editor.insertContent('[epic group=all view=compact users_per_page=10 show_result_count=yes  ]');
								}
							},

						]
					},

				]
			}
		});
	};

	tinymce.PluginManager.add( "epicShortcodes", epicShortcodesManager );
})();

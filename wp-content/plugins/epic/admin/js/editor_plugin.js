(
	function(){

		tinymce.create(
			"tinymce.plugins.epicShortcodes",
			{

				init: function(d,e) {},
				createControl:function(d,e)
				{

					if(d=="epic_shortcodes_button"){

						d=e.createMenuButton( "epic_shortcodes_button",{
							title:epicTmce.InsertepicShortcode,
							icons:false
							});

							var a=this;d.onRenderMenu.add(function(c,b){

								c=b.addMenu({title:epicTmce.LoginRegistrationForms});
									a.addImmediate(c,epicTmce.FrontRegistrationForm, '[epic_registration]');
									a.addImmediate(c,epicTmce.RegFormCustomRedirect, '[epic_registration redirect_to="http://url_here"]');
									a.addImmediate(c,epicTmce.RegFormCaptcha, '[epic_registration captcha=yes]');
									a.addImmediate(c,epicTmce.RegFormNoCaptcha, '[epic_registration captcha=no]');
									a.addImmediate(c,epicTmce.FrontLoginForm, '[epic_login]');
									a.addImmediate(c,epicTmce.SidebarLoginWidget, '[epic_login use_in_sidebar=yes]');
									a.addImmediate(c,epicTmce.LoginFormCustomRedirect, '[epic_login redirect_to="http://url_here"]');
									a.addImmediate(c,epicTmce.LogoutButton, '[epic_logout]');
									a.addImmediate(c,epicTmce.LogoutButtonCustomRedirect, '[epic_logout redirect_to="http://url_here"]');

								b.addSeparator();

								c=b.addMenu({title:epicTmce.SingleProfile});
										a.addImmediate(c,epicTmce.LoggedUserProfile,"[epic]" );
										a.addImmediate(c,epicTmce.LoggedUserProfileUserID,"[epic show_id=true]" );
										a.addImmediate(c,epicTmce.PostAuthorProfile,"[epic id=author]" );
										a.addImmediate(c,epicTmce.SpecificUserProfile,"[epic id=X]" );

										a.addImmediate(c,epicTmce.LoggedUserProfileHideStats,"[epic show_stats=no]" );
										a.addImmediate(c,epicTmce.LoggedUserProfileUserRole,"[epic show_role=yes]" );
										a.addImmediate(c,epicTmce.LoggedUserProfileStatus,"[epic show_profile_status=yes]" );
										a.addImmediate(c,epicTmce.LoggedUserProfileLogoutRedirect,"[epic logout_redirect=http://url_here]" );


								b.addSeparator();

								c=b.addMenu({title:epicTmce.MultipleProfilesMemberList});
									a.addImmediate(c,epicTmce.GroupSpecificUsers, '[epic group=user_id1,user_id2,user_id3,etc]');
									a.addImmediate(c,epicTmce.AllUsers, '[epic group=all users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersCompactView, '[epic group=all view=compact users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersCompactViewHalfWidth, '[epic group=all view=compact width=2 users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersModalWindow, '[epic group=all view=compact modal=yes users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersNewWindow, '[epic group=all view=compact new_window=yes users_per_page=10]');
									a.addImmediate(c,epicTmce.UsersBasedUserRole, '[epic group=all role=subscriber users_per_page=10]');
									a.addImmediate(c,epicTmce.AdministratorUsersOnly, '[epic group=all role=administrator users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersOrderedDisplayName, '[epic group=all order=asc orderby=display_name users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersOrderedPostCount, '[epic group=all order=desc orderby=post_count users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersOrderedRegistrationDate, '[epic group=all order=desc orderby=registered users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersOrderedCustomField, '[epic group=all order=desc orderby=custom_field_meta_key orderby_custom=yes users_per_page=10]');
									a.addImmediate(c,epicTmce.AllUsersUserID, '[epic group=all show_id=true users_per_page=10]');
									a.addImmediate(c,epicTmce.GroupUsersCustomField, '[epic group=all  group_meta=custom_field_key group_meta_value=custom_field_value users_per_page=10]');
									a.addImmediate(c,epicTmce.HideUsersUntilSearch, '[epic group=all users_per_page=10 hide_until_search=true]');
									a.addImmediate(c,epicTmce.SearchProfile, '[epic_search operator=OR]');
									a.addImmediate(c,epicTmce.SearchCustomFieldFilters, '[epic_search filters=meta1,meta2,meta3]');


								b.addSeparator();

								a.addImmediate(b,epicTmce.PrivateContentLoginRequired, '[epic_private]Place member only content here[/epic_private]');

								b.addSeparator();

								c=b.addMenu({title:epicTmce.ShortcodeOptionExamples});
									a.addImmediate(c,epicTmce.HideUserStatistics, '[epic show_stats=no]');
									a.addImmediate(c,epicTmce.HideUserSocialBar, '[epic show_social_bar=no]');
									a.addImmediate(c,epicTmce.HalfWidthProfileView, '[epic width=2]');
									a.addImmediate(c,epicTmce.CompactViewNoExtraFields, '[epic view=compact]');
									a.addImmediate(c,epicTmce.CustomizedProfileFields, '[epic view=meta_id1,meta_id2,meta_id3]');
									a.addImmediate(c,epicTmce.ShowUserIDProfiles, '[epic show_id=true]');
									a.addImmediate(c,epicTmce.LimitResultsMemberList, '[epic group=all view=compact limit_results=yes users_per_page=4 ]');
									a.addImmediate(c,epicTmce.ShowResultCountMemberList, '[epic group=all view=compact users_per_page=10 show_result_count=yes  ]');

							});
						return d

					} // End IF Statement

					return null
				},

				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}

			}
		);

		tinymce.PluginManager.add( "epicShortcodes", tinymce.plugins.epicShortcodes);
	}
)();

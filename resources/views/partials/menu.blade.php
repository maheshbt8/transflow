<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('img/Transflow-logo.png') }}" alt="Transflow" class="brand-image img-fluid" style="height: 30px" id="web_logo">
      <img src="{{ asset('img/Transflow-fav.png') }}" alt="Transflow" class="img-fluid" style="height: 30px" id="mobile_logo">
      <br/>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('admin') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
            @if(checkpermission('dashboard'))
            <li class="nav-item">
                <a href="{{ route("admin.home") }}" class="nav-link {{ request()->is('admin/home') || request()->is('admin/home/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    <p>{{ trans('global.dashboard') }}</p>
                </a>
            </li>
            @endif
            @if(checkallpermission(['permissions','roles','administrator','currency','system_setting','ratecard']))
                <li class="nav-item {{ side_menu_open(['permissions','roles','users','currency','system_setting','ratecard'])? 'menu-open' : '' }}">
                    <a class="nav-link {{ side_menu_open(['permissions','roles','users','currency','system_setting','ratecard'])? 'active' : '' }}" href="#">
                        <i class=" fas fa-chalkboard-teacher nav-icon"></i>
                        <p>{{ trans('cruds.adminManagement.title') }}<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(checkpermission('permissions'))
                        <li class="nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user-lock nav-icon">

                                </i>
                                <p>{{ trans('cruds.permission.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('roles'))
                        <li class="nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-tasks nav-icon">

                                </i>
                                <p>{{ trans('cruds.role.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('administrator'))
                        <li class="nav-item">
                            <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">

                                </i>
                                <p>{{ trans('cruds.user.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('currency'))    
                        <li class="nav-item">
                            <a href="{{ route('admin.currency.index') }}" class="nav-link">
                                <i class="fas fa-rupee-sign nav-icon"></i>
                               <p>Currency</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('languages'))    
                        <li class="nav-item">
                            <a href="{{ route('admin.languages.index') }}" class="nav-link">
                                <i class="fas fa-language nav-icon">

                                </i>
                             <p>Languages</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('ratecard'))    
                        <li class="nav-item">
                            <a href="{{ route('admin.ratecard.index') }}" class="nav-link">
                                
                                <i class="fa fa-credit-card nav-icon"  >

                                </i>
                            <p>Rate Card</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('terms'))   
                        <li class="nav-item">   
                            <a href="{{ route('admin.terms') }}" class="nav-link {{ side_menu_open(['speechtospeech'])? 'active' : '' }}">  
                                <i class="fas fa-language nav-icon"></i>    
                                <P>Terms and Condtions</P>  
                            </a>    
                        </li>   
                        @endif
                        @if(checkpermission('system_setting'))  
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="nav-link">
                                <i class="fas fa-language nav-icon">

                                </i>
                              <p>System Settings</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(checkallpermission(['org_manage','sub_organization_manage','departments_manage']))
                <li class="nav-item {{ side_menu_open(['org','suborganizations','departments'])? 'menu-open' : '' }}">
                    <a class="nav-link {{ side_menu_open(['org','suborganizations','departments'])? 'active' : '' }}" href="#">
                        <i class="fa-fw fas fa-building nav-icon">

                        </i>
                        <p>{{ trans('cruds.orgManagement.title') }}<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                    @if(checkpermission('org_manage'))
                        <li class="nav-item">
                            <a href="{{ route("admin.org.index") }}" class="nav-link {{ request()->is('admin/org') || request()->is('admin/org/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-building nav-icon">

                                </i>
                                <p>{{ trans('cruds.kptorganization.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('sub_organization_manage'))
                        <li class="nav-item">
                            <a href="{{ route("admin.suborganizations.index") }}" class="nav-link {{ request()->is('admin/suborganizations') || request()->is('admin/suborganizations/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-sitemap nav-icon"></i>
                                <p>{{ trans('cruds.kptsuborganization.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('departments_manage'))
                        <li class="nav-item">
                            <a href="{{ route("admin.departments.index") }}" class="nav-link {{ request()->is('admin/departments') || request()->is('admin/departments/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt nav-icon">
                                </i>
                                <p>{{ trans('cruds.kptdepartment.title') }}</p>
                            </a>
                        </li>
                        @endif

                    </ul>
                </li>
            @endif 
            @if(checkallpermission(['org_users_manage','sub_org_users_manage','department_users_manage','authenticate_user_manage']))
                <li class="nav-item {{ side_menu_open(['suborgusers','orgusers','departmentusers','authenticatedusers'])? 'menu-open' : '' }}">
               <a class="nav-link {{ side_menu_open(['suborgusers','orgusers','departmentusers','authenticatedusers'])? 'active' : '' }}" href="#">
                        <i class="fa-fw fas fa-users nav-icon">

                        </i>
                        <p>{{ trans('cruds.userManagement.title') }}<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        
                    @if(checkpermission('org_users_manage'))
                        <li class="nav-item">
                            <a href="{{ route("admin.orgusers.index") }}" class="nav-link {{ request()->is('admin/orgusers') || request()->is('admin/orgusers/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">

                                </i>
                                <p>{{ trans('cruds.kptorganization_users.title') }}</p>
                            </a>
                        </li>                       
                  @endif
                  
                  
                  @if(checkpermission('sub_org_users_manage'))  
                        <li class="nav-item {{ request()->is('admin/suborgusers') || request()->is('admin/suborgusers/*') ? 'menu-open' : '' }}" >
                            <a href="{{ route("admin.suborgusers.index") }}" class="nav-link {{ request()->is('admin/suborgusers') || request()->is('admin/suborgusers/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">
                                </i>
                                <p>{{ trans('cruds.kptsuborganization_users.title') }}</p>
                            </a>
                        </li>
                    @endif
                    
                    
                    @if(checkpermission('department_users_manage')) 
                        <li class="nav-item">
                            <a href="{{ route("admin.departmentusers.index") }}" class="nav-link {{ request()->is('admin/departmentusers') || request()->is('admin/departmentusers/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">
                                </i>
                                <p>{{ trans('cruds.kptdepartment_users.title') }}</p>
                            </a>
                        </li>
                    @endif
                    
                    
                    @if(checkpermission('authenticate_user_manage'))    
                        <li class="nav-item">
                            <a href="{{ route("admin.authenticatedusers.index") }}" class="nav-link {{ request()->is('admin/authenticatedusers') || request()->is('admin/authenticatedusers/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">

                                </i>
                                <p>{{ trans('cruds.kptauthenticated_users.title') }}</p>
                            </a>
                        </li>
                    @endif
                    
                        
                    </ul>
                </li>
            @endif
        @if(checkallpermission(['client_org_manage','client_suborg_manage','client_dept_manage','client_user']))
                <li class="nav-item {{ side_menu_open(['clientorg','client_sub_organization_manage','client_departments_manage','client'])? 'menu-open' : '' }}">
                    <a class="nav-link {{ side_menu_open(['clientorg','client_sub_organization_manage','client_departments_manage','client'])? 'active' : '' }}" href="#">
                        <i class="fa-fw fas fa-user-plus nav-icon">

                        </i>
                        <p>{{ trans('cruds.clientManagement.title') }}<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                    @if(checkpermission('client_org_manage'))
                        <li class="nav-item">
                            <a href="{{ route("admin.clientorg.index") }}" class="nav-link {{ request()->is('admin/clientorg') || request()->is('admin/clientorg/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-building nav-icon">

                                </i>
                                <p>{{ trans('cruds.kptorganization.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('client_suborg_manage'))
                        <li class="nav-item">
                            <a href="{{ route("admin.clientsuborg.index") }}" class="nav-link {{ request()->is('admin/suborganizations') || request()->is('admin/suborganizations/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-sitemap nav-icon">

                                </i>
                                <p>{{ trans('cruds.kptsuborganization.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('client_dept_manage'))
                        <li class="nav-item">
                            <a href="{{ route("admin.clientdept.index") }}" class="nav-link {{ request()->is('admin/departments') || request()->is('admin/departments/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt nav-icon">
                                </i>
                                <p>{{ trans('cruds.kptdepartment.title') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('client_user')) 
                        <li class="nav-item">
                            <a href="{{ route("admin.client.index") }}" class="nav-link {{ request()->is('admin/client') || request()->is('admin/client/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">

                                </i>
                                <p>Client User</p>
                                <!-- {{ trans('cruds.kptauthenticated_users.title') }} -->
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(checkpermission('finance'))
            <li class="nav-item {{ side_menu_open(['clientdetail','vendordetail'])? 'menu-open' : '' }}">
                    <a class="nav-link {{ side_menu_open(['clientdetail','vendordetail'])? 'active' : '' }}" href="#">
                        <i class="fas fa-balance-scale nav-icon">
                        </i>
                        <p>Finance<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                     <li class="nav-item">
                            <a href="{{ route("admin.finance.clientinvoice") }}" class="nav-link">
                                <i class="fas fa-receipt nav-icon">

                                </i>
                               <p>Client Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route("admin.finance.vendorinvoice") }}" class="nav-link">
                                <i class="fas fa-donate nav-icon">

                                </i>
                                <p>{{ getrolename('vendor') }}  Details</p>
                            </a>
                        </li>
                    </ul>
              </li>
              @endif
        <!-- Kreviewer menu block -->   
        @if(checkallpermission(['aem_kpt_pm_manage','aem_qatar_client_manage','aem_qatar_pm_manage','aem_kreviewer_manage','aem_qreviewer_manage','aem_translator_manage',]))       
        <li class="nav-item {{ side_menu_open(['kptaemrequest','kptaemqclientrequests','kptaemcpmrequest','kptaemtranslatorrequests','kptaemqreviewerrequests','kptaemkreviewerrequests'])? 'menu-open' : '' }}">
                    <a class="nav-link {{ side_menu_open(['kptaemrequest','kptaemqclientrequests','kptaemcpmrequest','kptaemtranslatorrequests','kptaemqreviewerrequests','kptaemkreviewerrequests'])? 'active' : '' }}" href="#">
                        <i class="fa-fw fas fa-industry nav-icon">
                        </i>
                        <p>{{ trans('cruds.AEMMgmt.title') }}<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                    @if(checkpermission('aem_kreviewer_manage'))
                        <li class="nav-item">
                        <a href="{{ route("admin.kptaemkreviewerrequests.index") }}" class="nav-link {{ request()->is('admin/kptaemkreviewerrequests') || request()->is('admin/kptaemkreviewerrequests/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list nav-icon">
                                </i>
                                <p>{{ trans('cruds.AEMMgmt.fields.Kreviewer') }} </p>
                            </a>
                        </li>
                    @endif
                     @if(checkpermission('aem_qreviewer_manage'))
                        <li class="nav-item">
                        <a href="{{ route("admin.kptaemqreviewerrequests.index") }}" class="nav-link {{ request()->is('admin/kptaemqreviewerrequests') || request()->is('admin/kptaemqreviewerrequests/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list nav-icon">
                                </i>
                                <p>{{ trans('cruds.AEMMgmt.fields.Qreviewer') }}</p>
                                <!-- {{ trans('cruds.translation.fields.doc') }} -->
                            </a>
                        </li>
                     @endif
                        @if(checkpermission('aem_translator_manage'))
                        <li class="nav-item">
                        <a href="{{ route("admin.kptaemtranslatorrequests.index") }}" class="nav-link {{ request()->is('admin/kptaemtranslatorrequests') || request()->is('admin/kptaemtranslatorrequests/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list nav-icon">
                                </i>
                                <p>{{ trans('cruds.AEMMgmt.fields.Translator') }}</p>
                                <!-- {{ trans('cruds.translation.fields.slider') }} -->
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('aem_qatar_pm_manage'))
                        <li class="nav-item">
                        <a href="{{ route("admin.kptaemcpmrequest.index") }}" class="nav-link {{ request()->is('admin/kptaemcpmrequest') || request()->is('admin/kptaemcpmrequest/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list nav-icon">
                                </i>
                                <p>{{ trans('cruds.AEMMgmt.fields.QatarPMManage') }}</p>
                                <!-- {{ trans('cruds.translation.fields.word') }} -->
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('aem_qatar_client_manage'))
                        <li class="nav-item">
                        <a href="{{ route("admin.kptaemqclientrequests.index") }}" class="nav-link {{ request()->is('admin/kptaemqclientrequests') || request()->is('admin/kptaemqclientrequests/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list nav-icon">
                                </i>
                                <p>{{ trans('cruds.AEMMgmt.fields.QatarClient') }}</p>
                                <!-- {{ trans('cruds.translation.fields.word') }} -->
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('aem_kpt_pm_manage'))
                        <li class="nav-item">
                        <a href="{{ route("admin.kptaemrequest.index") }}" class="nav-link {{ request()->is('admin/kptaemrequest') || request()->is('admin/kptaemrequest/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list nav-icon">
                                </i>
                                <p>{{ trans('cruds.AEMMgmt.fields.KPTPMManage') }}</p>
                                <!-- {{ trans('cruds.translation.fields.word') }} -->
                            </a>
                        </li>
                     @endif
                    </ul>
                </li>

         
        @endif
    <!-- KPT PM menu block -->
    @if(checkpermission('request_todo_activities'))
        <li class="nav-item">
                <a href="{{ route('admin.request.todoactivities') }}" class="nav-link {{ side_menu_open(['todoactivities'])? 'active' : '' }}">
                    <i class="nav-icon fas fa-fw fa-list">
                    </i>
                   <p>{{ trans('cruds.locrequest.fields.todoactivities') }}</p>
                </a>
            </li>
            @endif
            @if(checkpermission('qoute_generation'))    
                <li class="nav-item">
                    <a href="{{ route('admin.quotegeneration.index') }}" class="nav-link {{ side_menu_open(['quotegeneration'])? 'active' : '' }}">
                        <i class="fas fa-quote-left nav-icon"></i>
                        <P>Quote Generation</P>
                    </a>
                </li>
            @endif
            @if(checkpermission('marketing_campaign'))
            <li class="nav-item">
                <a href="{{ route('admin.marketingcampaign.index') }}" class="nav-link {{ side_menu_open(['marketingcampaign'])? 'active' : '' }}">
                    <i class="nav-icon fas fa-bullhorn"></i>
                   <p>{{ trans('cruds.marketingcampaign.title') }}</p>
                </a>
            </li>
            @endif
            @if(checkpermission('create_request'))
            <!-- <li class="nav-item">
                <a href="{{ route('admin.request.index') }}" class="nav-link {{ side_menu_open(['request'])? 'active' : '' }}">
                    <i class="nav-icon fa-fw fas fa-plus-square"></i>
                   
                   <p>{{ trans('cruds.locrequest.fields.addprojects') }}</p>
                </a>
            </li> -->
            @endif
            @if(checkpermission('email_settings'))
            <li class="nav-item">
                <a href="{{ route('admin.emailsettings.index') }}" class="nav-link {{ side_menu_open(['emailsettings'])? 'active' : '' }}">
                    <i class="nav-icon fas fa-envelope"></i>
                   <p>{{ trans('cruds.e_settings.title') }}</p>
                </a>
            </li>
            @endif
         @if(checkallpermission(['translation_text','translation_doc','translation_slider','translation_wordtovec','kpt_romanization']))
            <li class="nav-item {{ side_menu_open(['translationtext','translationdoc','translationslide','translationword'])? 'menu-open' : '' }}">
                    <a class="nav-link {{ side_menu_open(['translationtext','translationdoc','translationslide','translationword'])? 'active' : '' }}" href="#">
                        <i class="fa-fw fas fa-industry nav-icon">
                        </i>
                        <p>{{ trans('cruds.translation.menu') }}<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                    @if(checkpermission('translation_text'))
                        <li class="nav-item">
                            <a href="{{ route("admin.translation.translationtext") }}" class="nav-link {{ request()->is('admin/translationtext') ? 'active' : '' }}">
                                <i class="fa fa-file-pdf nav-icon">
                                </i>
                                <p>{{ trans('cruds.translation.fields.text') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('translation_doc'))
                        <li class="nav-item">
                            <a href="{{ route("admin.translation.translationdoc") }}" class="nav-link {{ request()->is('admin/translationdoc') ? 'active' : '' }}">
                                <i class="fa fa-file-word nav-icon">
                                </i>
                                <p>{{ trans('cruds.translation.fields.doc') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('translation_slider'))
                        <li class="nav-item">
                            <a href="{{ route("admin.translation.translationslide") }}" class="nav-link {{ request()->is('admin/translationslide') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-list nav-icon">
                                </i>
                                <p>{{ trans('cruds.translation.fields.slider') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('translation_wordtovec'))
                        <li class="nav-item">
                            <a href="{{ route("admin.translation.translationword") }}" class="nav-link {{ request()->is('admin/translationword') ? 'active' : '' }}">
                                <i class="fa fa-file-video nav-icon">
                                </i>
                                <p>{{ trans('cruds.translation.fields.word') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(checkpermission('kpt_romanization'))    
                        <li class="nav-item">
                            <a href="{{ route("admin.kptromanization.addRomanization") }}" class="nav-link {{ request()->is('admin/kptromanization') || request()->is('admin/kptromanization/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-file-excel-o nav-icon">

                                </i>
                                <p>{{ trans('cruds.kptromanization.menu') }}</p>
                            </a>
                        </li>
                    @endif
            
                    </ul>
                </li>

            @endif
            @if(checkpermission('translation_memory'))
            <li class="nav-item">
                <a href="{{ route('admin.translationmemory.index') }}" class="nav-link {{ side_menu_open(['translationmemory'])? 'active' : '' }}">
                    <i class="fas fa-trademark nav-icon">
                    </i>
                   <p>{{ trans('cruds.translation_memory.menu') }}</p>
                </a>
            </li>
            @endif
            @if(checkpermission('file_translation'))            
            <li class="nav-item">
                <a href="{{ route('admin.translationcsvfile.index') }}" class="nav-link {{ side_menu_open(['translationcsvfile'])? 'active' : '' }}">
                    <i class="nav-icon fa fa-file">
                    </i>
                    <p>File Translation</p>
                </a>
            </li>
            @endif
            @if(checkpermission('ocrpdf'))          
            <li class="nav-item">
                <a href="{{ route('admin.ocrpdf.index') }}" class="nav-link {{ side_menu_open(['ocrpdf'])? 'active' : '' }}">
                    <i class="nav-icon fa fa-file-pdf"></i>
                    <p>{{ trans('cruds.ocrpdf.menu') }}</p>
                </a>
            </li>
            @endif
            @if(checkpermission('transflow_samples'))   
                <li class="nav-item">
                    <a href="{{ route("admin.transflowsamples.index") }}" class="nav-link {{ side_menu_open(['transflowsamples'])? 'active' : '' }}">
                        <!-- <i class="fa-fw fas file-invoice nav-icon"> -->
                        <i class="fas fa-file-invoice nav-icon"></i>
                        <P>{{ trans('cruds.transflowsample.menu') }}</P>
                    </a>
                </li>
            @endif
            @if(checkpermission('videototext')) 
                <li class="nav-item">
                    <a href="{{ route('admin.videototext.index') }}" class="nav-link {{ side_menu_open(['videototext'])? 'active' : '' }}">
                        <i class=" nav-icon fa fa-file-video"></i>
                        <P>{{ trans('cruds.videototext.menu') }}</P>
                    </a>
                </li>
            @endif
            @if(checkpermission('xliff_segmentation'))  
                <li class="nav-item">
                    <a href="{{ route('admin.xliff.index') }}" class="nav-link {{ side_menu_open(['xliff'])? 'active' : '' }}">
                        <i class="fa-fw fas fa-user nav-icon"></i>
                       <P>XLIFF Segmentation</P>
                    </a>
                </li>
            @endif
            @if(checkpermission('speech_to_speech_tool'))   
                <li class="nav-item">
                    <a href="{{ route('admin.speechtospeech.index') }}" class="nav-link {{ side_menu_open(['speechtospeech'])? 'active' : '' }}">
                        <i class="fas fa-language nav-icon"></i>
                        <P>speech to speech tool</P>
                    </a>
                </li>
            @endif
        </ul>

    </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
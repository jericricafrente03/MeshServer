<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="/images/mgmt88-logo.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<img src="/images/mgmt88-pharmacy.png"  style="width: 80%; margin-top: 5px;">
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
                <li>
                    <a href="/admin">
                        <div class="parent-icon"><i class='bx  bx-home'></i>
                        </div>
                        <div class="menu-title">Executive Dashboard</div>
                    </a>
                </li>

            @can(['menu-stores.*'])
                @foreach($menuStores as $menu)
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-buildings' ></i>
                            </div>
                            <div class="menu-title">{{ $menu->code }}</div>
                        </a>
                        <ul>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-menu"></i>Operations</a>
                                <ul>
                                    <li> <a href="/admin/store/operation/{{$menu->id}}/rts"><i class="bx bx-right-arrow-alt"></i>RTS</a></li>
                                    <li> <a href="/admin/store/operation/{{$menu->id}}/mail-operation"><i class="bx bx-right-arrow-alt"></i>Mail Orders</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-menu"></i>Clinical</a>
                                <ul>
                                    <li> <a href="/admin/store/clinical/{{$menu->id}}/patients"><i class="bx bx-right-arrow-alt"></i>Patients</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-menu"></i>Procurement</a>
                                <ul>
                                    <li> <a href="/admin/store/procurement/{{$menu->id}}/orders"><i class="bx bx-right-arrow-alt"></i>Orders</a></li>
                                    <li> <a href="/admin/store/procurement/{{$menu->id}}/returns"><i class="bx bx-right-arrow-alt"></i>Returns</a></li>
                                    <li> <a href="/admin/store/procurement/{{$menu->id}}/clinical-orders"><i class="bx bx-right-arrow-alt"></i>Clinical Orders</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-menu"></i>Commpliance</a>
                                <ul>
                                    <li> <a href="/admin/store/compliance/{{$menu->id}}/audit"><i class="bx bx-right-arrow-alt"></i>Audit</a></li>
                                    <li> <a href="/admin/store/compliance/{{$menu->id}}/documents"><i class="bx bx-right-arrow-alt"></i>Documents</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul>
                            <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-menu"></i>Patient Support</a>
                                <ul>
                                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-menu"></i>Transfer RX</a>
                                        <ul>
                                            <li> <a href="/admin/transfer_rx/1/tribe_members"><i class="bx bx-right-arrow-alt"></i>Tribe Members</a>
                                            </li>
                                            <li> <a href="/admin/transfer_rx/2/tribe_members"><i class="bx bx-right-arrow-alt"></i>Tribe Members Outside</a>
                                            </li>
                                            <li> <a href="/admin/transfer_rx/3/tribe_members"><i class="bx bx-right-arrow-alt"></i>General</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li> <a href="/admin/store/patien-support/{{$menu->id}}/patient-database"><i class="bx bx-right-arrow-alt"></i>Patient Database</a></li>
                                    <li> <a href="/admin/store/patien-support/{{$menu->id}}/escalation"><i class="bx bx-right-arrow-alt"></i>Escalation</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endforeach
            @endcan

            @canany(['user.index', 'role.index'])
                <li class="menu-label">System Settings</li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-user' ></i>
                        </div>
                        <div class="menu-title">System Users</div>
                    </a>
                    <ul>
                        @can('user.index')
                            <li> <a href="/admin/user"><i class="bx bx-right-arrow-alt"></i>Users</a></li>
                        @endcan
                        @can('role.index')
                            <li> <a href="/admin/role"><i class="bx bx-right-arrow-alt"></i>Roles</a></li>
                        @endcan
                        @can('rbac.index')
                            <li> <a href="/admin/rbac"><i class="bx bx-right-arrow-alt"></i>RBAC</a></li>
                        @endcan
						
                    </ul>
					
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-store'></i>
                        </div>
                        <div class="menu-title">Pharmacy Settings</div>
                    </a>
                    <ul>
                        
                            <li> <a href="/admin/divisiontwob/pharmacy_store"><i class="bx bx-right-arrow-alt"></i>Pharmacy Stores</a></li>
                            <li> <a href="/admin/divisiontwob/pharmacy_operation"><i class="bx bx-right-arrow-alt"></i>Pharmacy Operations</a></li>
						
                    </ul>
					
                </li>

                @can('division-1.index')
                    <li>
                        <a href="/admin/divisionone">
                            <div class="parent-icon"><i class='bx  bx-building  '></i>
                            </div>
                            <div class="menu-title">Division 1</div>
                        </a>
                    </li>
                @endcan


				@canany(['division-2a.uof.index', 'division-2a.b2b.index', 'division-2a.d2c.index', 'division-2a.data-and-reporting.index'])
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-buildings' ></i>
                            </div>
                            <div class="menu-title">Division 2A</div>
                        </a>
                        <ul>
                            @can('division-2a.uof.index')
                                <li>
                                    <a href="/admin/divisiontwoa/ubacare_order_fulfillment"><i class="bx bx-right-arrow-alt"></i>Ubacare Order Fulfillment </a>
                                </li>
                            @endcan
                            @can('division-2a.b2b.index')
                                <li>
                                    <a href="/admin/divisiontwoa/b2b"><i class="bx bx-right-arrow-alt"></i>B2B</a>
                                </li>
                            @endcan
                            @can('division-2a.d2c.index')
                                <li>
                                    <a href="/admin/divisiontwoa/d2c"><i class="bx bx-right-arrow-alt"></i>D2C</a>
                                </li>
                            @endcan
                            @can('division-2a.data-and-reporting.index')
                                <li>
                                    <a href="/admin/divisiontwoa/data_and_reporting"><i class="bx bx-right-arrow-alt"></i>Data & Reporting</a>
                                </li>
                            @endcan
                            
                        </ul>
                    </li>
                @endcanany


                @canany(['division-2b.patients.index', 'division-2b.mail-orders.index', 'division-2b.pharmacy.index', 'division-2b.pharmacy-support.index'])
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-buildings' ></i>
                            </div>
                            <div class="menu-title">Division 2B</div>
                        </a>
                        <ul>
                            @can('division-2b.patients.index')
                                <li>
                                    <a href="/admin/divisiontwob/patients"><i class="bx bx-right-arrow-alt"></i> Patients</a>
                                </li>
                            @endcan
                            @can('division-2b.mail-orders.index')
                                <li>
                                    <a href="/admin/divisiontwob/mail_orders"><i class="bx bx-right-arrow-alt"></i> Mail Orders</a>
                                </li>
                            @endcan
                            @can('division-2b.pharmacy.index')
                                <li>
                                    <a href="/admin/divisiontwob/pharmacy"><i class="bx bx-right-arrow-alt"></i>Pharmacy</a>
                                </li>
                            @endcan
                            @can('division-2b.pharmacy-support.index')
                                <li>
                                    <a href="/admin/divisiontwob/pharmacy_support"><i class="bx bx-right-arrow-alt"></i>Pharmacy Support</a>
                                </li>
                            @endcan
							

                        </ul>
                    </li>
                @endcanany
				
                @canany(['division-3.tasks.index', 'division-3.monthly-clinical-report.index', 'division-3.outcomes.index', 'division-3.d1-telebridge.index', 'division-3.d2a-telebridge.index', 'division-3.d2b-telebridge.index'])
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-buildings' ></i>
                            </div>
                            <div class="menu-title">Division 3</div>
                        </a>
                        <ul>
                            @can('division-3.tasks.index')
                                <li>
                                    <a href='/admin/divisionthree/task'><i class="bx bx-right-arrow-alt"></i>Tasks</a>
                                </li>
                            @endcan
                            @can('division-3.monthly-clinical-report.index')
                                <li>
                                    <a href='/admin/division3/monthly_report'><i class="bx bx-right-arrow-alt"></i>Adherence</a>
                                </li>
                            @endcan
                            @can('division-3.outcomes.index')
                                <li>
                                    <a href='/admin/telehealth/outcomes'><i class="bx bx-right-arrow-alt"></i>Outcomes</a>
                                </li>
                            @endcan
                            @can('division-3.d1-telebridge.index')
                                <li>
                                    <a href="/admin/divisionthree/divisionone_telebridge"><i class="bx bx-right-arrow-alt"></i>Division 1 Telebridge</a>
                                </li>
                            @endcan
                            @can('division-3.d2a-telebridge.index')
                                <li>
                                    <a href="/admin/divisionthree/divisiontwoa"><i class="bx bx-right-arrow-alt"></i>Division 2A</a>
                                </li>
                            @endcan
                            @can('division-3.d2b-telebridge.index')
                                <li>
                                    <a href='/admin/divisionthree/divisiontwob'><i class="bx bx-right-arrow-alt"></i>Division 2B</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['division-4.sales.index', 'division-4.marketing.index'])
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-buildings' ></i>
                            </div>
                            <div class="menu-title">Division 4</div>
                        </a>
                        <ul>
                            @can('division-4.sales.index')
                                <li>
                                    <a href="/admin/divisionfour/sales"><i class="bx bx-right-arrow-alt"></i>Sales</a>
                                </li>
                            @endcan
                            @can('division-4.marketing.index')
                                <li>
                                    <a href="/admin/divisionfour/marketing"><i class="bx bx-right-arrow-alt"></i>Marketing</a>
                                </li>
                            @endcan
                        </ul>
                    </li>    
                @endcanany

                @canany(['customer-support.sales.index', 'customer-support.marketing.index'])
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-support' ></i>
                            </div>
                            <div class="menu-title">Customer Support</div>
                        </a>
                        <ul>
                            @can('customer-support.sales.index')
                                <li>
                                    <a href="/admin/customer_support/sales"><i class="bx bx-right-arrow-alt"></i>Sales</a>
                                </li>
                            @endcan
                            @can('customer-support.marketing.index')
                                <li>
                                    <a href="/admin/customer_support/marketing"><i class="bx bx-right-arrow-alt"></i>Marketing</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['accounting.sales-monitoring.index', 'accounting.payroll-percentage.index', 'accounting.ar-aging.index', 'accounting.profitability.index', 'accounting.partnership-reconcillation.index', 'accounting.accounts-payable.index'])
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-money' ></i>
                            </div>
                            <div class="menu-title">Accounting</div>
                        </a>
                        <ul>
                            @can('accounting.sales-monitoring.index')
                                <li>
                                    <a href="/admin/accounting/sales_monitoring"><i class="bx bx-right-arrow-alt"></i>Sales Monitoring</a>
                                </li>
                            @endcan
                            @can('accounting.payroll-percentage.index')
                                <li>
                                    <a href="/admin/accounting/payroll_percentage"><i class="bx bx-right-arrow-alt"></i>Payroll Percentage</a>
                                </li>
                            @endcan
                            @can('accounting.ar-aging.index')
                                <li>
                                    <a href="/admin/accounting/ar_aging"><i class="bx bx-right-arrow-alt"></i>AR Aging</a>
                                </li>
                            @endcan
                            @can('accounting.profitability.index')
                                <li>
                                    <a href="/admin/accounting/profitability"><i class="bx bx-right-arrow-alt"></i>Profitability</a>
                                </li>
                            @endcan
                            @can('accounting.partnership-reconcillation.index')
                                <li>
                                    <a href="/admin/accounting/partnership_reconcillation"><i class="bx bx-right-arrow-alt"></i>Partnership Reconcillation</a>
                                </li>
                            @endcan
                            @can('accounting.accounts-payable.index')
                                <li>
                                    <a href="/admin/accounting/accounts_payable"><i class="bx bx-right-arrow-alt"></i>Accounts Payable</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

        
                @canany(['human-resource.employees.index', 'human-resource.recruitment-and-hiring.index'])
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-user-circle' ></i>
                            </div>
                            <div class="menu-title">Human Resource</div>
                        </a>
                        <ul>
                            @can('human-resource.employees.index')
                                <li>
                                    <a href="/admin/human_resources/employees_relations"><i class="bx bx-right-arrow-alt"></i>Employees</a>
                                </li>
                            @endcan
                            @can('human-resource.recruitment-and-hiring.index')
                                <li>
                                    <a href="/admin/human_resources/recruitment_and_hiring"><i class="bx bx-right-arrow-alt"></i>Recruitment & Hiring</a>
                                </li>
                            @endcan
                            @can('human-resource.announcements.index')
                                <li>
                                    <a href="/admin/human_resources/announcements"><i class="bx bx-right-arrow-alt"></i>Announcements</a>
                                </li>
                            @endcan			
                        </ul>
                    </li>
                @endcanany
                


                  <li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-check-shield' ></i>
						</div>
						<div class="menu-title">Compliance & Regulatory</div>
					</a>
					<ul>
                        <li> <a href="/admin/oig_check"><i class="bx bx-right-arrow-alt"></i>OIG Check</a>
						<li> <a href="/admin/oig_list"><i class="bx bx-right-arrow-alt"></i>OIG List</a>
						<li> <a href="/admin/compliance_and_regulatory/licensure"><i class="bx bx-right-arrow-alt"></i>Licensure</a></li>
						<li> <a href="/admin/compliance_and_regulatory/audits"><i class="bx bx-right-arrow-alt"></i>Audits</a></li>
						<li> <a href="/admin/compliance_and_regulatory/provider_manuals"><i class="bx bx-right-arrow-alt"></i>Provider Manuals</a></li>
						<li> <a href="/admin/compliance_and_regulatory/bop"><i class="bx bx-right-arrow-alt"></i>BOP</a></li>
						
					</ul>
				</li>
            @endcanany	
			

			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->

@canany(['super-admin', 'admin'])
<li class="menu-label">OLD MENU (to be removed)</li>

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

@endcanany
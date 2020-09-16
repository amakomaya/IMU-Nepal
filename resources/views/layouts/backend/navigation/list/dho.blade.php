<li>
    <a href="#">
        <i class="fa fa-building-o" aria-hidden="true"></i>
        Related Offices <span class="fa arrow"></span>
    </a>

    <ul class="nav nav-second-level">
         <li>
            <a href="{{ route('municipality.index') }}">
                    Municipality 
            </a>
        </li>
         <li>
            <a href="{{ route('ward.index') }}">
                    Ward 
            </a>
        </li>
         <li>
            <a href="{{ route('healthpost.index') }}">
                    Healthpost 
            </a>
        </li>
    </ul>
</li> 
<li>
    <a href="{{ route('health-worker.index') }}">
    <i class="fa fa-user"></i>
        Health Worker
    </a>
</li>
<li>
    <a href="{{ route('woman.index') }}">
        <i class="fa fa-users" aria-hidden="true"></i>
        Patients
    </a>
</li>
<li>
    <a href="{{ route('center.woman.map') }}">
    <i class="fa fa-map-marker"></i>
            Map 
    </a>
</li>
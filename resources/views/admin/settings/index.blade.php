@extends('layouts.master')

@section('contents')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">System Configuration</h1>
        <p class="text-slate-500">Manage global farm parameters and AI intelligence thresholds.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
            <i class='bx bx-check-circle text-xl'></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-8">
            @foreach($settings as $group => $groupSettings)
                <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
                    <div class="px-8 py-6 bg-slate-50 border-bottom border-slate-100">
                        <h2 class="text-lg font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                            <i class='bx bx-cog text-primary'></i>
                            {{ ucfirst($group) }} Settings
                        </h2>
                    </div>
                    <div class="p-8 space-y-8">
                        @foreach($groupSettings as $setting)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1">{{ $setting->label }}</label>
                                    <p class="text-xs text-slate-500 leading-relaxed">{{ $setting->description }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    @if($setting->key == 'farm_region')
                                        <select id="region-select" name="settings[{{ $setting->key }}]" 
                                                class="form-control w-full !bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                                            <option value="">Select Region</option>
                                        </select>
                                    @elseif($setting->key == 'farm_province')
                                        <select id="province-select" name="settings[{{ $setting->key }}]" 
                                                data-selected="{{ $setting->value }}"
                                                class="form-control w-full !bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                                            <option value="">Select Province</option>
                                        </select>
                                    @elseif($setting->type == 'string')
                                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ old('settings.'.$setting->key, $setting->value) }}"
                                               class="form-control w-full !bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                                    @elseif($setting->type == 'text')
                                        <textarea name="settings[{{ $setting->key }}]" rows="4"
                                                  class="form-control w-full !bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">{{ old('settings.'.$setting->key, $setting->value) }}</textarea>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="flex items-center justify-end gap-4 pb-12">
                <button type="submit" class="ti-btn ti-btn-primary-full !rounded-xl !px-10 !py-4 !text-base shadow-lg">
                    Apply Changes
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const phData = {
        "NCR": ["Metro Manila"],
        "CAR": ["Abra", "Apayao", "Benguet", "Ifugao", "Kalinga", "Mountain Province"],
        "Region I": ["Ilocos Norte", "Ilocos Sur", "La Union", "Pangasinan"],
        "Region II": ["Batanes", "Cagayan", "Isabela", "Nueva Vizcaya", "Quirino"],
        "Region III": ["Aurora", "Bataan", "Bulacan", "Nueva Ecija", "Pampanga", "Tarlac", "Zambales"],
        "Region IV-A": ["Batangas", "Cavite", "Laguna", "Quezon", "Rizal"],
        "MIMAROPA": ["Marinduque", "Occidental Mindoro", "Oriental Mindoro", "Palawan", "Romblon"],
        "Region V": ["Albay", "Camarines Norte", "Camarines Sur", "Catanduanes", "Masbate", "Sorsogon"],
        "Region VI": ["Aklan", "Antique", "Capiz", "Guimaras", "Iloilo", "Negros Occidental"],
        "Region VII": ["Bohol", "Cebu", "Negros Oriental", "Siquijor"],
        "Region VIII": ["Biliran", "Eastern Samar", "Leyte", "Northern Samar", "Samar", "Southern Leyte"],
        "Region IX": ["Zamboanga del Norte", "Zamboanga del Sur", "Zamboanga Sibugay"],
        "Region X": ["Bukidnon", "Camiguin", "Lanao del Norte", "Misamis Occidental", "Misamis Oriental"],
        "Region XI": ["Davao de Oro", "Davao del Norte", "Davao del Sur", "Davao Occidental", "Davao Oriental"],
        "Region XII": ["Cotabato", "Sarangani", "South Cotabato", "Sultan Kudarat"],
        "Region XIII": ["Agusan del Norte", "Agusan del Sur", "Dinagat Islands", "Surigao del Norte", "Surigao del Sur"],
        "BARMM": ["Basilan", "Lanao del Sur", "Maguindanao", "Sulu", "Tawi-Tawi"]
    };

    const regionSelect = document.getElementById('region-select');
    const provinceSelect = document.getElementById('province-select');
    const currentRegion = "{{ \App\Models\SystemSetting::get('farm_region') }}";
    const currentProvince = provinceSelect ? provinceSelect.getAttribute('data-selected') : "";

    function populateRegions() {
        if (!regionSelect) return;
        Object.keys(phData).sort().forEach(region => {
            const opt = document.createElement('option');
            opt.value = region;
            opt.textContent = region;
            if (region === currentRegion) opt.selected = true;
            regionSelect.appendChild(opt);
        });
        
        if (currentRegion) {
            updateProvinces(currentRegion);
        }
    }

    function updateProvinces(region) {
        if (!provinceSelect) return;
        provinceSelect.innerHTML = '<option value="">Select Province</option>';
        
        if (region && phData[region]) {
            phData[region].sort().forEach(province => {
                const opt = document.createElement('option');
                opt.value = province;
                opt.textContent = province;
                if (province === currentProvince) opt.selected = true;
                provinceSelect.appendChild(opt);
            });
        }
    }

    if (regionSelect) {
        regionSelect.addEventListener('change', (e) => {
            updateProvinces(e.target.value);
        });
    }

    document.addEventListener('DOMContentLoaded', populateRegions);
</script>
@endsection

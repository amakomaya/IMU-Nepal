@extends('backend.woman.form')

    @section('action',route('woman.register-again-store', $data->id))

    @section('methodField',method_field('PUT'))

    @section ('name',!!old('name')? old('name') : $data->name)

    @section ('mool_darta_no',old('mool_darta_no'))

    @section ('sewa_darta_no',old('sewa_darta_no'))

    @section ('orc_darta_no',old('orc_darta_no'))

    @section ('phone',!!old('phone')? old('phone') : $data->phone)

    @section ('height',!!old('height')? old('height') : $data->height)

    @section ('age',old('age'))

    @section ('lmp_date_en',old('lmp_date_en'))

    @section ('blood_group',!!old('blood_group')? old('blood_group') : $data->blood_group)

    @php($province_id = !!old('province_id')? old('province_id') : $data->province_id)

    @php($district_id = !!old('district_id')? old('district_id') : $data->district_id)

    @php($municipality_id = !!old('municipality_id')? old('municipality_id') : $data->municipality_id)

    @section ('tole',!!old('tole')? old('tole') : $data->tole)

    @section ('ward',!!old('ward')? old('ward') : $data->ward)

    @section ('husband_name',!!old('husband_name')? old('husband_name') : $data->husband_name)

    @php($anc_status= old('anc_status')) 

    @php($delivery_status= old('delivery_status')) 

    @php($pnc_status= old('pnc_status')) 

    @php($labtest_status= old('labtest_status')) 

    @php($registered_device= old('registered_device')) 

    @section ('created_by',old('created_by'))

    @section ('longitude',!!old('longitude')? old('longitude') : $data->longitude)

    @section ('latitude',!!old('latitude')? old('latitude') : $data->latitude)

    @php($status= old('status'))

    @section ('email',!!old('email')? old('email') : $user->email)

    @php($pregancy = 'again');
@extends('backend.woman.anc.form')

    @section('action',route('anc.update', $data->id))

    @section('methodField', "")

    @section ('token',old('token'))

    @section ('woman_token',old('woman_token'))

    @section ('visit_date',old('visit_date'))

    @section ('weight',old('weight'))

    @section ('anemia',old('anemia'))

    @section ('swell',old('swell'))

    @section ('blood_pressure',old('blood_pressure'))

    @section ('uterus_height',old('uterus_height'))

    @section ('baby_presentation',old('baby_presentation'))

    @section ('baby_heart_beat',old('baby_heart_beat'))

    @section ('other',old('other'))

    @section ('iron_pills',old('iron_pills'))

    @section ('worm_medicine',old('worm_medicine'))

    @section ('td_vaccine',old('td_vaccine'))

    @section ('checked_by',old('checked_by'))

    @section ('checked_by_healthpost',old('checked_by_healthpost'))

    @php($hp_code= old('hp_code')) 

    @php($status= old('status')) 
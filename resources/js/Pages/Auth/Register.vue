<script setup>
import { ref, computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    nombre: '',
    apellido1: '',
    apellido2: '',
    fecha_nacimiento: '',
    telefono: '',
    direccion: '',
    ciudad: '',
    provincia: '',
    codigo_postal: '',
    mismo_direccion_facturacion: true,
    direccion_facturacion: '',
    ciudad_facturacion: '',
    provincia_facturacion: '',
    codigo_postal_facturacion: '',
    instrumento_preferido: '',
    nivel_experiencia: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const focusedField = ref(null);
const touchedFields = ref({});
const fechaDisplay = ref('');

const onFechaInput = (e) => {
    const raw = e.target.value;
    const digits = raw.replace(/\D/g, '').slice(0, 8);
    let formatted = '';
    if (digits.length > 0) formatted = digits.slice(0, 2);
    if (digits.length > 2) formatted += '/' + digits.slice(2, 4);
    if (digits.length > 4) formatted += '/' + digits.slice(4, 8);
    fechaDisplay.value = formatted;
    if (digits.length === 8) {
        form.fecha_nacimiento = `${digits.slice(4, 8)}-${digits.slice(2, 4)}-${digits.slice(0, 2)}`;
    } else {
        form.fecha_nacimiento = '';
    }
};

const markTouched = (field) => {
    touchedFields.value[field] = true;
};

const fieldClass = (field, hasValue) => {
    const classes = [];
    if (focusedField.value === field) classes.push('ring-2 ring-[#E87F24]');
    if (form.errors[field]) classes.push('border-red-500 ring-1 ring-red-500');
    else if (touchedFields.value[field] && hasValue) classes.push('border-green-500');
    return classes.join(' ');
};

const passwordStrength = computed(() => {
    const p = form.password;
    if (!p) return 0;
    
    let score = 0;
    if (p.length >= 8) score++;
    if (p.length >= 12) score++;
    if (/[a-z]/.test(p) && /[A-Z]/.test(p)) score++;
    if (/\d/.test(p)) score++;
    if (/[^a-zA-Z0-9]/.test(p)) score++;
    
    return score;
});

const strengthLabel = computed(() => {
    const s = passwordStrength.value;
    if (s <= 1) return { label: 'Débil', color: 'bg-red-500' };
    if (s <= 3) return { label: 'Media', color: 'bg-yellow-500' };
    return { label: 'Fuerte', color: 'bg-green-500' };
});

const isValidForm = computed(() => {
    return form.nombre && 
           form.fecha_nacimiento &&
           form.telefono &&
           form.direccion &&
           form.ciudad &&
           form.provincia &&
           form.codigo_postal &&
           form.email &&
           form.password &&
           form.password_confirmation &&
           form.password === form.password_confirmation &&
           passwordStrength.value >= 2;
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Registrarse" />

        <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <InputLabel for="nombre" value="Nombre *" />
                    <TextInput
                        id="nombre"
                        type="text"
                        class="mt-1 block w-full transition-colors"
                        :class="fieldClass('nombre', form.nombre)"
                        v-model="form.nombre"
                        required
                        autofocus
                        @focus="focusedField = 'nombre'"
                        @blur="focusedField = null; markTouched('nombre')"
                    />
                    <InputError class="mt-2" :message="form.errors.nombre" />
                </div>

                <div>
                    <InputLabel for="apellido1" value="Primer apellido" />
                    <TextInput
                        id="apellido1"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('apellido1', form.apellido1)"
                        v-model="form.apellido1"
                        @focus="focusedField = 'apellido1'"
                        @blur="focusedField = null; markTouched('apellido1')"
                    />
                </div>

                <div>
                    <InputLabel for="apellido2" value="Segundo apellido" />
                    <TextInput
                        id="apellido2"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('apellido2', form.apellido2)"
                        v-model="form.apellido2"
                        @focus="focusedField = 'apellido2'"
                        @blur="focusedField = null; markTouched('apellido2')"
                    />
                </div>

                <div>
                    <InputLabel for="fecha_nacimiento" value="Fecha de nacimiento *" />
                    <input
                        id="fecha_nacimiento"
                        type="text"
                        placeholder="DD/MM/AAAA"
                        class="mt-1 block w-full rounded-md border-[#73A5CA] shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24] bg-[#FEFDDF]"
                        :class="fieldClass('fecha_nacimiento', form.fecha_nacimiento)"
                        :value="fechaDisplay"
                        @input="onFechaInput"
                        required
                        @focus="focusedField = 'fecha_nacimiento'"
                        @blur="focusedField = null; markTouched('fecha_nacimiento')"
                    />
                    <InputError class="mt-2" :message="form.errors.fecha_nacimiento" />
                </div>

                <div>
                    <InputLabel for="telefono" value="Teléfono (con código internacional) *" />
                    <TextInput
                        id="telefono"
                        type="tel"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('telefono', form.telefono)"
                        v-model="form.telefono"
                        placeholder="+34 612 345 678"
                        required
                        @focus="focusedField = 'telefono'"
                        @blur="focusedField = null; markTouched('telefono')"
                    />
                    <InputError class="mt-2" :message="form.errors.telefono" />
                </div>
            </div>

            <div class="border-t border-[#73A5CA] pt-4 mt-4">
                <h3 class="text-[#1a1a1a] font-medium mb-3">Dirección de envío</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel for="direccion" value="Dirección *" />
                    <TextInput
                        id="direccion"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('direccion', form.direccion)"
                        v-model="form.direccion"
                        placeholder="Calle, número, piso..."
                        required
                        @focus="focusedField = 'direccion'"
                        @blur="focusedField = null; markTouched('direccion')"
                    />
                        <InputError class="mt-2" :message="form.errors.direccion" />
                    </div>

                    <div>
                        <InputLabel for="ciudad" value="Ciudad *" />
                    <TextInput
                        id="ciudad"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('ciudad', form.ciudad)"
                        v-model="form.ciudad"
                        required
                        @focus="focusedField = 'ciudad'"
                        @blur="focusedField = null; markTouched('ciudad')"
                    />
                        <InputError class="mt-2" :message="form.errors.ciudad" />
                    </div>

                    <div>
                        <InputLabel for="provincia" value="Provincia *" />
                    <TextInput
                        id="provincia"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('provincia', form.provincia)"
                        v-model="form.provincia"
                        required
                        @focus="focusedField = 'provincia'"
                        @blur="focusedField = null; markTouched('provincia')"
                    />
                        <InputError class="mt-2" :message="form.errors.provincia" />
                    </div>

                    <div>
                        <InputLabel for="codigo_postal" value="Código postal *" />
                    <TextInput
                        id="codigo_postal"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('codigo_postal', form.codigo_postal && form.codigo_postal.length === 5)"
                        v-model="form.codigo_postal"
                        maxlength="5"
                        placeholder="12345"
                        required
                        @focus="focusedField = 'codigo_postal'"
                        @blur="focusedField = null; markTouched('codigo_postal')"
                    />
                        <InputError class="mt-2" :message="form.errors.codigo_postal" />
                    </div>
                </div>
            </div>

            <div class="border-t border-[#73A5CA] pt-4 mt-4">
                <div class="flex items-center mb-3">
                    <input
                        id="mismo_direccion_facturacion"
                        type="checkbox"
                        v-model="form.mismo_direccion_facturacion"
                        class="mr-2 rounded border-gray-300 text-[#E87F24] focus:ring-[#E87F24]"
                    />
                    <label for="mismo_direccion_facturacion" class="text-[#1a1a1a] text-sm">
                        La dirección de facturación es la misma que la de envío
                    </label>
                </div>

                <div v-if="!form.mismo_direccion_facturacion" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <InputLabel for="direccion_facturacion" value="Dirección de facturación" />
                        <TextInput
                            id="direccion_facturacion"
                            type="text"
                            class="mt-1 block w-full bg-[#FEFDDF]"
                            v-model="form.direccion_facturacion"
                        />
                    </div>

                    <div>
                        <InputLabel for="ciudad_facturacion" value="Ciudad facturación" />
                        <TextInput
                            id="ciudad_facturacion"
                            type="text"
                            class="mt-1 block w-full bg-[#FEFDDF]"
                            v-model="form.ciudad_facturacion"
                        />
                    </div>

                    <div>
                        <InputLabel for="provincia_facturacion" value="Provincia facturación" />
                        <TextInput
                            id="provincia_facturacion"
                            type="text"
                            class="mt-1 block w-full bg-[#FEFDDF]"
                            v-model="form.provincia_facturacion"
                        />
                    </div>

                    <div>
                        <InputLabel for="codigo_postal_facturacion" value="Código postal facturación" />
                        <TextInput
                            id="codigo_postal_facturacion"
                            type="text"
                            class="mt-1 block w-full bg-[#FEFDDF]"
                            v-model="form.codigo_postal_facturacion"
                            maxlength="5"
                        />
                    </div>
                </div>
            </div>

            <div class="border-t border-[#73A5CA] pt-4 mt-4">
                <h3 class="text-[#1a1a1a] font-medium mb-3">Preferencias (opcional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="instrumento_preferido" value="Instrumento preferido" />
                        <select
                            id="instrumento_preferido"
                            class="mt-1 block w-full bg-[#FEFDDF] border border-[#73A5CA] text-[#1a1a1a] px-4 py-2 rounded"
                            v-model="form.instrumento_preferido"
                        >
                            <option value="">Selecciona...</option>
                            <option value="guitarra">Guitarra</option>
                            <option value="bajo">Bajo</option>
                            <option value="bateria">Batería</option>
                            <option value="teclado">Teclado</option>
                            <option value="viento">Viento</option>
                            <option value="indiferente">Indiferente</option>
                        </select>
                    </div>

                    <div>
                        <InputLabel for="nivel_experiencia" value="Nivel de experiencia" />
                        <select
                            id="nivel_experiencia"
                            class="mt-1 block w-full bg-[#FEFDDF] border border-[#73A5CA] text-[#1a1a1a] px-4 py-2 rounded"
                            v-model="form.nivel_experiencia"
                        >
                            <option value="">Selecciona...</option>
                            <option value="principiante">Principiante</option>
                            <option value="intermedio">Intermedio</option>
                            <option value="avanzado">Avanzado</option>
                            <option value="profesional">Profesional</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#73A5CA] pt-4 mt-4">
                <div>
                    <InputLabel for="email" value="Email *" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('email', form.email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email))"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        @focus="focusedField = 'email'"
                        @blur="focusedField = null; markTouched('email')"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Contraseña *" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('password', form.password && passwordStrength >= 2)"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        @focus="focusedField = 'password'"
                        @blur="focusedField = null; markTouched('password')"
                    />
                    <div v-if="form.password" class="mt-2">
                        <label class="text-xs text-[#1a1a1a]/70">Fortaleza:</label>
                        <meter :value="passwordStrength" min="0" max="5" class="w-full h-2 mt-1"></meter>
                        <span class="text-xs" :class="strengthLabel.color.includes('red') ? 'text-red-500' : strengthLabel.color.includes('yellow') ? 'text-yellow-500' : 'text-green-500'">
                            {{ strengthLabel.label }}
                        </span>
                    </div>
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirmar contraseña *" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="fieldClass('password_confirmation', form.password_confirmation && form.password_confirmation === form.password)"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        @focus="focusedField = 'password_confirmation'"
                        @blur="focusedField = null; markTouched('password_confirmation')"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-[#E87F24] underline hover:text-[#FFC81E]"
                >
                    ¿Ya tienes cuenta?
                </Link>

                <PrimaryButton
                    v-if="isValidForm && !form.processing"
                    class="px-6"
                    :disabled="form.processing"
                >
                    Registrarse
                </PrimaryButton>
                <div v-else-if="form.processing" class="flex items-center gap-2 text-sm text-[#E87F24]">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    Registrando...
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
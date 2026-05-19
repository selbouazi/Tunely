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
                        :class="focusedField === 'nombre' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.nombre"
                        required
                        autofocus
                        @focus="focusedField = 'nombre'"
                        @blur="focusedField = null"
                    />
                    <InputError class="mt-2" :message="form.errors.nombre" />
                </div>

                <div>
                    <InputLabel for="apellido1" value="Primer apellido" />
                    <TextInput
                        id="apellido1"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="focusedField === 'apellido1' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.apellido1"
                        @focus="focusedField = 'apellido1'"
                        @blur="focusedField = null"
                    />
                </div>

                <div>
                    <InputLabel for="apellido2" value="Segundo apellido" />
                    <TextInput
                        id="apellido2"
                        type="text"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="focusedField === 'apellido2' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.apellido2"
                        @focus="focusedField = 'apellido2'"
                        @blur="focusedField = null"
                    />
                </div>

                <div>
                    <InputLabel for="fecha_nacimiento" value="Fecha de nacimiento *" />
                    <TextInput
                        id="fecha_nacimiento"
                        type="date"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="focusedField === 'fecha_nacimiento' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.fecha_nacimiento"
                        required
                        @focus="focusedField = 'fecha_nacimiento'"
                        @blur="focusedField = null"
                    />
                    <InputError class="mt-2" :message="form.errors.fecha_nacimiento" />
                </div>

                <div>
                    <InputLabel for="telefono" value="Teléfono (con código internacional) *" />
                    <TextInput
                        id="telefono"
                        type="tel"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="focusedField === 'telefono' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.telefono"
                        placeholder="+34 612 345 678"
                        required
                        @focus="focusedField = 'telefono'"
                        @blur="focusedField = null"
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
                            :class="focusedField === 'direccion' ? 'ring-2 ring-[#E87F24]' : ''"
                            v-model="form.direccion"
                            placeholder="Calle, número, piso..."
                            required
                            @focus="focusedField = 'direccion'"
                            @blur="focusedField = null"
                        />
                        <InputError class="mt-2" :message="form.errors.direccion" />
                    </div>

                    <div>
                        <InputLabel for="ciudad" value="Ciudad *" />
                        <TextInput
                            id="ciudad"
                            type="text"
                            class="mt-1 block w-full bg-[#FEFDDF]"
                            :class="focusedField === 'ciudad' ? 'ring-2 ring-[#E87F24]' : ''"
                            v-model="form.ciudad"
                            required
                            @focus="focusedField = 'ciudad'"
                            @blur="focusedField = null"
                        />
                        <InputError class="mt-2" :message="form.errors.ciudad" />
                    </div>

                    <div>
                        <InputLabel for="provincia" value="Provincia *" />
                        <TextInput
                            id="provincia"
                            type="text"
                            class="mt-1 block w-full bg-[#FEFDDF]"
                            :class="focusedField === 'provincia' ? 'ring-2 ring-[#E87F24]' : ''"
                            v-model="form.provincia"
                            required
                            @focus="focusedField = 'provincia'"
                            @blur="focusedField = null"
                        />
                        <InputError class="mt-2" :message="form.errors.provincia" />
                    </div>

                    <div>
                        <InputLabel for="codigo_postal" value="Código postal *" />
                        <TextInput
                            id="codigo_postal"
                            type="text"
                            class="mt-1 block w-full bg-[#FEFDDF]"
                            :class="focusedField === 'codigo_postal' ? 'ring-2 ring-[#E87F24]' : ''"
                            v-model="form.codigo_postal"
                            maxlength="5"
                            placeholder="12345"
                            required
                            @focus="focusedField = 'codigo_postal'"
                            @blur="focusedField = null"
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
                        class="mr-2"
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
                        :class="focusedField === 'email' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        @focus="focusedField = 'email'"
                        @blur="focusedField = null"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Contraseña *" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full bg-[#FEFDDF]"
                        :class="focusedField === 'password' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        @focus="focusedField = 'password'"
                        @blur="focusedField = null"
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
                        :class="focusedField === 'password_confirmation' ? 'ring-2 ring-[#E87F24]' : ''"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        @focus="focusedField = 'password_confirmation'"
                        @blur="focusedField = null"
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
                    class="px-6"
                    :class="{ 'opacity-25': form.processing || !isValidForm }"
                    :disabled="form.processing || !isValidForm"
                >
                    Registrarse
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
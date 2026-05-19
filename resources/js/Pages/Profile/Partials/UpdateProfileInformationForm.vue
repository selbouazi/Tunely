<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    telefono: user.telefono || '',
    direccion: user.direccion || '',
    ciudad: user.ciudad || '',
    provincia: user.provincia || '',
    codigo_postal: user.codigo_postal || '',
    direccion_facturacion: user.direccion_facturacion || '',
    ciudad_facturacion: user.ciudad_facturacion || '',
    provincia_facturacion: user.provincia_facturacion || '',
    codigo_postal_facturacion: user.codigo_postal_facturacion || '',
    fecha_nacimiento: user.fecha_nacimiento || '',
    instrumento_preferido: user.instrumento_preferido || '',
    nivel_experiencia: user.nivel_experiencia || '',
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Información del perfil
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Actualiza la información de tu perfil y email.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <InputLabel for="name" value="Nombre" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="telefono" value="Teléfono" />
                    <TextInput id="telefono" type="text" class="mt-1 block w-full" v-model="form.telefono" />
                    <InputError class="mt-2" :message="form.errors.telefono" />
                </div>

                <div>
                    <InputLabel for="fecha_nacimiento" value="Fecha de nacimiento" />
                    <TextInput id="fecha_nacimiento" type="date" class="mt-1 block w-full" v-model="form.fecha_nacimiento" />
                    <InputError class="mt-2" :message="form.errors.fecha_nacimiento" />
                </div>
            </div>

            <h3 class="font-medium text-gray-900 pt-2">Dirección de envío</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <InputLabel for="direccion" value="Dirección" />
                    <TextInput id="direccion" type="text" class="mt-1 block w-full" v-model="form.direccion" />
                    <InputError class="mt-2" :message="form.errors.direccion" />
                </div>
                <div>
                    <InputLabel for="ciudad" value="Ciudad" />
                    <TextInput id="ciudad" type="text" class="mt-1 block w-full" v-model="form.ciudad" />
                    <InputError class="mt-2" :message="form.errors.ciudad" />
                </div>
                <div>
                    <InputLabel for="provincia" value="Provincia" />
                    <TextInput id="provincia" type="text" class="mt-1 block w-full" v-model="form.provincia" />
                    <InputError class="mt-2" :message="form.errors.provincia" />
                </div>
                <div>
                    <InputLabel for="codigo_postal" value="Código postal" />
                    <TextInput id="codigo_postal" type="text" class="mt-1 block w-full" v-model="form.codigo_postal" />
                    <InputError class="mt-2" :message="form.errors.codigo_postal" />
                </div>
            </div>

            <h3 class="font-medium text-gray-900 pt-2">Dirección de facturación</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <InputLabel for="dir_fact" value="Dirección" />
                    <TextInput id="dir_fact" type="text" class="mt-1 block w-full" v-model="form.direccion_facturacion" />
                    <InputError class="mt-2" :message="form.errors.direccion_facturacion" />
                </div>
                <div>
                    <InputLabel for="ciudad_fact" value="Ciudad" />
                    <TextInput id="ciudad_fact" type="text" class="mt-1 block w-full" v-model="form.ciudad_facturacion" />
                    <InputError class="mt-2" :message="form.errors.ciudad_facturacion" />
                </div>
                <div>
                    <InputLabel for="provincia_fact" value="Provincia" />
                    <TextInput id="provincia_fact" type="text" class="mt-1 block w-full" v-model="form.provincia_facturacion" />
                    <InputError class="mt-2" :message="form.errors.provincia_facturacion" />
                </div>
                <div>
                    <InputLabel for="cp_fact" value="Código postal" />
                    <TextInput id="cp_fact" type="text" class="mt-1 block w-full" v-model="form.codigo_postal_facturacion" />
                    <InputError class="mt-2" :message="form.errors.codigo_postal_facturacion" />
                </div>
            </div>

            <h3 class="font-medium text-gray-900 pt-2">Preferencias</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <InputLabel for="instrumento_preferido" value="Instrumento preferido" />
                    <TextInput id="instrumento_preferido" type="text" class="mt-1 block w-full" v-model="form.instrumento_preferido" />
                    <InputError class="mt-2" :message="form.errors.instrumento_preferido" />
                </div>
                <div>
                    <InputLabel for="nivel_experiencia" value="Nivel de experiencia" />
                    <select id="nivel_experiencia" v-model="form.nivel_experiencia" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-[#E87F24] focus:ring-[#E87F24]">
                        <option value="">Seleccionar...</option>
                        <option value="principiante">Principiante</option>
                        <option value="intermedio">Intermedio</option>
                        <option value="avanzado">Avanzado</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.nivel_experiencia" />
                </div>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Tu email no está verificado.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#E87F24] focus:ring-offset-2"
                    >
                        Haz clic aquí para reenviar el email de verificación.
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                    Se ha enviado un nuevo enlace de verificación a tu email.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Guardado.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

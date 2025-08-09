<x-app-layout>
    <div x-data="{
        flashMessage: '{{ \Illuminate\Support\Facades\Session::get('flash_message') }}',
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', { message: this.flashMessage }), 200);
            }
        }
    }" class="container mx-auto lg:w-2/3 p-5">
        {{-- <x-auth-validation-errors class="mb-4" :errors="$errors" /> --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="bg-white p-3 shadow rounded-lg md:col-span-2">
                <form x-data="{
                    countries: {{ json_encode($countries) }},
                    billingAddress: {{ json_encode([
                        'address1' => old('billing_address1', $billingAddress->address1),
                        'address2' => old('billing_address2', $billingAddress->address2),
                        'city' => old('billing_city', $billingAddress->city),
                        'state' => old('billing_state', $billingAddress->state),
                        'country_code' => old('billing_country_code', $billingAddress->country_code),
                        'zipcode' => old('billing_zipcode', $shippingAddress->zipcode),
                    ]) }},
                    shippingAddress: {{ json_encode([
                        'address1' => old('shipping_address1', $shippingAddress->address1),
                        'address2' => old('shipping_address2', $shippingAddress->address2),
                        'city' => old('shipping_city', $shippingAddress->city),
                        'state' => old('shipping_state', $shippingAddress->state),
                        'country_code' => old('shipping_country_code', $shippingAddress->country_code),
                        'zipcode' => old('shipping_zipcode', $shippingAddress->zipcode),
                    ]) }},
                    get billingCountryStates() {
                        const country = this.countries.find(country => country.code === this.billingAddress.country_code);
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    },
                    get shippingCountryStates() {
                        const country = this.countries.find(country => country.code === this.shippingAddress.country_code);
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    },
                }"action="{{ route('profile.update') }}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-2">Profile Details</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-text-input type="text" name="first_name"
                            value="{{ old('first_name', $customer->first_name) }}" placeholder="First Name"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />

                        <x-text-input type="text" name="last_name"
                            value="{{ old('last_name', $customer->last_name) }}" placeholder="Last Name"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input type="text" name="email" value="{{ old('email', $user->email) }}"
                            placeholder="Your Email"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                            placeholder="Your Phone Number"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>
                    <h2 class="text-xl font-semibold mb-2">Billing Address</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-text-input type="text" name="billing[address1]" x-model="billingAddress.address1"
                            placeholder="Address Line 1"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />

                        <x-text-input type="text" name="billing[address2]" x-model="billingAddress.address2"
                            placeholder="Address Line 2"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-text-input type="text" name="billing[city]" x-model="billingAddress.city"
                            placeholder="City"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />

                        <x-text-input type="text" name="billing[zipcode]" x-model="billingAddress.zipcode"
                            placeholder="ZipCode"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-select-input type="select" name="billing[country_code]"
                            x-model="billingAddress.country_code" placeholder="Select Country"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2">
                            <option value="">Select Country</option>
                            <template x-for="country in countries" :key="country.code">
                                <option :selected="country.code === billingAddress.country_code" :value="country.code"
                                    x-text="country.name"></option>
                            </template>
                        </x-select-input>
                        <div>
                            <template x-if="billingCountryStates">
                                <x-select-input type="select" name="billing[state]" x-model="billingAddress.state"
                                    placeholder="Select State"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2">
                                    <option value="">Select State</option>
                                    <template x-for="[code, state] of Object.entries(billingCountryStates)"
                                        :key="code">
                                        <option :selected="code === billingAddress.state" :value="code"
                                            x-text="state"></option>
                                    </template>
                                </x-select-input>
                            </template>
                            <template x-if="!billingCountryStates">
                                <x-text-input type="text" name="billing[state]" x-model="billingAddress.state"
                                    placeholder="State"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                            </template>
                        </div>

                    </div>

                    <div class="flex justify-between mt-6 mb-2">
                        <h2 class="text-xl font-semibold">Shipping Address</h2>
                        <label for="sameAsBillingAddress" class="text-gray-700">
                            <input @change="$event.target.checked ? shippingAddress = { ...billingAddress} : ''"
                                type="checkbox" id="sameAsBillingAddress"
                                class="text-purple-600 focus:ring-purple-600 mr-2" />
                            Same as Billing
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-text-input type="text" name="shipping[address1]" x-model="shippingAddress.address1"
                            placeholder="Address Line 1"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />

                        <x-text-input type="text" name="shipping[address2]" x-model="shippingAddress.address2"
                            placeholder="Address Line 2"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-text-input type="text" name="shipping[city]" x-model="shippingAddress.city"
                            placeholder="City"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />

                        <x-text-input type="text" name="shipping[zipcode]" x-model="shippingAddress.zipcode"
                            placeholder="ZipCode"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-select-input type="select" name="shipping[country_code]"
                            x-model="shippingAddress.country_code" placeholder="Select Country"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2">
                            <option value="">Select Country</option>
                            <template x-for="country in countries" :key="country.code">
                                <option :selected="country.code === shippingAddress.country_code" :value="country.code"
                                    x-text="country.name"></option>
                            </template>
                        </x-select-input>
                        <div>
                            <template x-if="shippingCountryStates">
                                <x-select-input type="select" name="shipping[state]" x-model="shippingAddress.state"
                                    placeholder="Select State"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2">
                                    <option value="">Select State</option>
                                    <template x-for="[code, state] of Object.entries(shippingCountryStates)"
                                        :key="code">
                                        <option :selected="code === shippingAddress.state" :value="code"
                                            x-text="state"></option>
                                    </template>
                                </x-select-input>
                            </template>
                            <template x-if="!shippingCountryStates">
                                <x-text-input type="text" name="shipping[state]" x-model="shippingAddress.state"
                                    placeholder="State"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                            </template>
                        </div>
                    </div>


                    <x-primary-button class="w-full mt-5">Update</x-primary-button>
                </form>
            </div>
            <div class="bg-white p-3 shadow rounded-lg">
                <form action="{{ route('profile_password.update') }}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-2">Update Password</h2>
                    <div class="mb-3">
                        <x-text-input type="password" name="old_password" placeholder="Current Password"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input type="password" name="new_password" placeholder="New Password"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>
                    <div class="mb-3">
                        <x-text-input type="password" name="new_password_confirmation"
                            placeholder="Confirm New Password"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-1 border-gray-300 rounded p-2" />
                    </div>
                    <x-primary-button class="w-full">Update Password</x-primary-button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>

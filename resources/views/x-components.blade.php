@extends('tyro-dashboard::layouts.admin')


@push('styles')
<x-tyro-dashboard::component-styles />
<script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')

<div class="container mx-auto px-4 py-8" x-data="{}">
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Dashboard Components</h1>
        <p class="text-muted-foreground mt-2">Beautiful, reusable components for your dashboard</p>
    </div>

    @php
    $kpis = [
        [
            'label' => 'Monthly Revenue',
            'value' => '$48,230',
            'color' => 'success',
            'trend_type' => 'up',
            'trend_text' => '+12.4% vs last month',
            'icon' => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>'
        ],
        [
            'label' => 'New Signups',
            'value' => '1,284',
            'color' => 'primary',
            'trend_type' => 'up',
            'trend_text' => '+6.1% this week',
            'icon' => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>'
        ],
        [
            'label' => 'Open Tickets',
            'value' => '42',
            'color' => 'warning',
            'trend_type' => 'down',
            'trend_text' => '-3 since yesterday',
            'icon' => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>'
        ],
        [
            'label' => 'Error Rate',
            'value' => '0.18%',
            'color' => 'danger',
            'trend_type' => 'up',
            'trend_text' => '+0.03% today',
            'icon' => '<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>'
        ]
    ];
    @endphp

    <section class="mb-12">
        <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Info Cards</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($kpis as $kpi)
            <x-tyro-dashboard::infocard :label="$kpi['label']" :value="$kpi['value']" :icon="$kpi['icon']" :iconColor="$kpi['color']" :trendType="$kpi['trend_type']" :trendText="$kpi['trend_text']" />
            @endforeach
        </div>
    </section>

    <div class="space-y-12">
        <!-- Basic Inputs Section -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Basic Inputs</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-tyro-dashboard::text name="username" label="Username" placeholder="Enter your username" help="Choose a unique username" required />

                <x-tyro-dashboard::email name="email" label="Email Address" placeholder="your@email.com" required />

                <x-tyro-dashboard::url name="website" label="Website URL" placeholder="https://example.com" />

                <x-tyro-dashboard::phonenumber name="phone" label="Phone Number" format="us" />
            </div>

            <div class="mt-6">
                <x-tyro-dashboard::textarea name="bio" label="Biography" placeholder="Tell us about yourself..." rows="4" />
            </div>
        </section>

        <!-- Icon Inputs -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Icon Inputs</h2>
            <form action="#" method="GET" onsubmit="return false;">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <x-tyro-dashboard::icon-input 
                        name="search" 
                        label="Search" 
                        type="search"
                        placeholder="Search anything..." 
                        icon="fa-solid fa-magnifying-glass"
                        autocomplete="off"
                    />

                    <x-tyro-dashboard::icon-input 
                        name="price" 
                        label="Financial Input" 
                        placeholder="0.00" 
                        icon="fa-solid fa-dollar-sign"
                        position="left"
                        autocomplete="off"
                    />

                    <x-tyro-dashboard::icon-input 
                        name="username_icon" 
                        label="Right Icon" 
                        placeholder="username" 
                        icon="fa-solid fa-user"
                        position="right"
                        autocomplete="username"
                    />

                    <x-tyro-dashboard::icon-input 
                        name="email_small" 
                        label="Small Size" 
                        placeholder="email@example.com" 
                        icon="fa-solid fa-envelope"
                        size="sm"
                        autocomplete="email"
                    />

                    <x-tyro-dashboard::icon-input 
                        name="website_large" 
                        label="Large Size" 
                        placeholder="https://..." 
                        icon="fa-solid fa-globe"
                        size="lg"
                        autocomplete="url"
                    />

                    <x-tyro-dashboard::icon-input 
                        name="password_icon" 
                        label="Password" 
                        type="password" 
                        placeholder="••••••••" 
                        icon="fa-solid fa-lock"
                        autocomplete="current-password"
                    />

                    <x-tyro-dashboard::icon-input 
                        name="error_icon" 
                        label="With Error" 
                        placeholder="Error state" 
                        icon="fa-solid fa-triangle-exclamation"
                        error="This field is required"
                    />

                    <x-tyro-dashboard::icon-input 
                        name="custom_svg" 
                        label="Custom SVG Icon" 
                        placeholder="Handwritten..." 
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>'
                    />
                </div>
            </form>
        </section>

        <!-- Selection Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Selection Components</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-tyro-dashboard::select 
                    name="country" 
                    label="Country" 
                    :options="['us' => 'United States', 'uk' => 'United Kingdom', 'ca' => 'Canada', 'au' => 'Australia', 'de' => 'Germany', 'fr' => 'France', 'jp' => 'Japan']" 
                    placeholder="Select a country" 
                    searchable
                />

                <x-tyro-dashboard::country name="country_full" label="Full Country List (Searchable)" />
                <x-tyro-dashboard::currency name="currency" label="Currency" />
                <x-tyro-dashboard::timezone name="timezone" label="Timezone" />

                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Searchable & Clearable (Default Soft)</h3>
                    <x-tyro-dashboard::multiselect 
                        name="skills_search" 
                        label="Skills" 
                        :options="[
                            ['value' => 'php', 'label' => 'PHP'],
                            ['value' => 'js', 'label' => 'JavaScript'],
                            ['value' => 'python', 'label' => 'Python'],
                            ['value' => 'laravel', 'label' => 'Laravel'],
                            ['value' => 'vue', 'label' => 'Vue.js'],
                            ['value' => 'react', 'label' => 'React'],
                            ['value' => 'tailwind', 'label' => 'Tailwind CSS']
                        ]" 
                        :value="['php', 'laravel']"
                        placeholder="Search & select skills..."
                    />
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Solid Success Variant (Max 2)</h3>
                    <x-tyro-dashboard::multiselect 
                        name="hobbies" 
                        label="Choose up to 2 Hobbies" 
                        variant="solid"
                        color="success"
                        :max="2"
                        :options="['Reading', 'Gaming', 'Cooking', 'Hiking', 'Photography']"
                        help="You can only select a maximum of 2 hobbies"
                    />
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Outline Info Variant</h3>
                    <x-tyro-dashboard::multiselect 
                        name="frameworks" 
                        label="Preferred Frameworks" 
                        variant="outline"
                        color="info"
                        :options="['Next.js', 'Nuxt.js', 'SvelteKit', 'Remix', 'Astro']"
                    />
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Destructive Solid</h3>
                    <x-tyro-dashboard::multiselect 
                        name="alerts" 
                        label="System Alerts to Enable" 
                        variant="solid"
                        color="destructive"
                        :options="['High CPU', 'Low Memory', 'Disk Full', 'Network Down']"
                        :value="['High CPU']"
                    />
                </div>
            </div>

            <div class="mt-6">
                <x-tyro-dashboard::radiogroup name="subscription" label="Subscription Plan" :options="['free' => 'Free', 'pro' => 'Pro ($9/month)', 'enterprise' => 'Enterprise ($99/month)']" inline />
            </div>

            <div class="mt-6">
                <x-tyro-dashboard::checkboxes name="interests" label="Interests" :options="['tech' => 'Technology', 'design' => 'Design', 'business' => 'Business', 'marketing' => 'Marketing']" inline />
            </div>
        </section>

        <!-- Toggle Switches -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Toggle Switches</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-tyro-dashboard::toggle name="toggle_default" label="Default (Primary)" checked />
                <x-tyro-dashboard::toggle name="toggle_red" label="Red Toggle" color="red" checked />
                <x-tyro-dashboard::toggle name="toggle_yellow" label="Yellow Toggle" color="yellow" checked />
                <x-tyro-dashboard::toggle name="toggle_green" label="Green Toggle" color="green" checked />
                <x-tyro-dashboard::toggle name="toggle_pink" label="Pink Toggle" color="pink" checked />
                <x-tyro-dashboard::toggle name="toggle_cyan" label="Cyan Toggle" color="cyan" checked />
                <x-tyro-dashboard::toggle name="toggle_gray" label="Gray Toggle" color="gray" checked />
                <x-tyro-dashboard::toggle name="toggle_purple" label="Purple Toggle" color="purple" checked />
                <x-tyro-dashboard::toggle name="toggle_orange" label="Orange Toggle" color="orange" checked />
                <x-tyro-dashboard::toggle name="toggle_blue" label="Blue Toggle" color="blue" checked />
                <x-tyro-dashboard::toggle name="toggle_disabled" label="Disabled Toggle" disabled />
                <x-tyro-dashboard::toggle name="toggle_help" label="Toggle with Help" help="This is a helpful description" />
            </div>
        </section>

        <!-- Star Rating -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Premium Star Ratings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-tyro-dashboard::star-rating name="rating_basic" label="Basic Rating" :value="3" help="Default interactive rating system" />

                <x-tyro-dashboard::star-rating name="rating_half" label="Half Star Support" :value="3.5" allowHalf color="orange" help="Precision rating with half-step increments" />

                <x-tyro-dashboard::star-rating name="rating_readonly" label="Read-only Display" :value="4" readonly color="success" help="Used for displaying existing reviews" />

                <x-tyro-dashboard::star-rating name="rating_sm" label="Small Size" :value="2" size="sm" color="destructive" />

                <x-tyro-dashboard::star-rating name="rating_lg" label="Large Size" :value="5" size="lg" color="purple" help="Prominent rating for featured items" />

                <x-tyro-dashboard::star-rating name="rating_primary" label="Primary Theme" :value="4" color="primary" help="Customizable brand colors" />
            </div>
        </section>

        <!-- Specialized Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Specialized Components</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-8">
            </div>

                <div class="md:col-span-2 space-y-8">
                    <h3 class="text-xl font-bold border-b pb-2">Modern Tag Inputs</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <x-tyro-dashboard::tags name="tags_soft" label="Soft Variant (Default)" :value="['Laravel', 'Livewire', 'Alpine']" placeholder="Add tag..." />

                        <x-tyro-dashboard::tags name="tags_solid" label="Solid Variant" variant="solid" color="success" :value="['Design', 'Product', 'Release']" />

                        <x-tyro-dashboard::tags name="tags_outline" label="Outline Variant" variant="outline" color="info" :value="['Draft', 'Pending']" />

                        <x-tyro-dashboard::tags name="tags_destructive" label="Destructive Solid" variant="solid" color="destructive" :value="['Urgent', 'Bug']" />
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm font-medium text-muted-foreground">Diverse Colors & Sizes</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <x-tyro-dashboard::tags name="tags_purple_sm" label="Small Purple" color="purple" size="sm" :value="['mini', 'tag']" />

                            <x-tyro-dashboard::tags name="tags_orange_lg" label="Large Orange" color="orange" size="lg" :value="['Large', 'Labels']" />

                            <x-tyro-dashboard::tags name="tags_pink" label="Pink Soft" color="pink" :value="['Style']" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <x-tyro-dashboard::tags name="tags_max" label="Max Tags (Limit: 3)" :max="3" :value="['One', 'Two']" help="You can only add up to 3 tags" />

                        <x-tyro-dashboard::tags name="tags_clear" label="Clearable & Multiple Input" clearable :value="['React', 'Vue', 'Next.js']" help="You can add multiple tags separated by comma" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Modern Time Picker Redesign -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Modern Time Picker</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-tyro-dashboard::timepicker name="time_24h" label="24-Hour Format" value="14:30" help="Default 24-hour mode" />

                <x-tyro-dashboard::timepicker name="time_12h" label="12-Hour Format" :enable24Hour="false" value="02:30 PM" help="12-hour mode with AM/PM" />

                <x-tyro-dashboard::timepicker name="time_increment" label="15-Minute Increment" :minuteIncrement="15" value="09:45" help="Minutes increment by 15" />
            </div>
        </section>

        <!-- Modern Date & Calendar Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Modern Calendar & Date Pickers</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Inline Calendar -->
                <div class="space-y-4 md:col-span-2 lg:col-span-1">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Inline Calendar (Single Select)</h3>
                    <x-tyro-dashboard::calendar name="event_date" label="Single Date Selection" mode="single" placeholder="Pick a day" help="Classic single date picker with popover" />
                </div>

                <!-- Range Selection -->
                <div class="space-y-4 md:col-span-2 lg:col-span-1">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Date Range</h3>
                    <x-tyro-dashboard::calendar name="date_range" label="Check-in / Check-out" mode="range" help="Click to select start and end dates" />
                </div>
            </div>
        </section>

        <!-- Upload Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Upload Components</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-tyro-dashboard::imageupload name="avatar" label="Profile Picture" help="Maximum file size: 2MB" />

                <x-tyro-dashboard::fileupload name="documents" label="Upload Documents" multiple help="Maximum file size: 10MB per file" />
            </div>
        </section>

        <!-- Rich Content Editors -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Rich Content Editors</h2>

            <div class="space-y-6">
                <x-tyro-dashboard::richtext name="description" label="Rich Text Editor" height="250px" help="Use the toolbar to format your text" />

                <x-tyro-dashboard::markdown name="content" label="Markdown Editor" height="250px" help="Write in Markdown and preview the result" />
            </div>
        </section>

        <!-- Display Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Display Components</h2>

            <div class="space-y-6">
                <!-- Buttons -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">Premium Buttons</h3>

                    <div class="space-y-8">
                        <!-- Solid Buttons -->
                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-4">Solid Variant (Default)</p>
                            <div class="flex flex-wrap gap-4">
                                <x-tyro-dashboard::button>Primary</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button color="destructive">Destructive</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button color="success">Success</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button color="warning">Warning</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button color="info">Info</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button color="purple">Purple</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button color="orange">Orange</x-tyro-dashboard::button>
                            </div>
                        </div>

                        <!-- Soft Buttons -->
                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-4">Soft Variant</p>
                            <div class="flex flex-wrap gap-4">
                                <x-tyro-dashboard::button variant="soft">Primary</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="soft" color="destructive">Destructive</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="soft" color="success">Success</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="soft" color="warning">Warning</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="soft" color="blue">Blue</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="soft" color="pink">Pink</x-tyro-dashboard::button>
                            </div>
                        </div>

                        <!-- Outline Buttons -->
                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-4">Outline Variant</p>
                            <div class="flex flex-wrap gap-4">
                                <x-tyro-dashboard::button variant="outline">Primary</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="outline" color="destructive">Destructive</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="outline" color="success">Success</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button variant="outline" color="cyan">Cyan</x-tyro-dashboard::button>
                            </div>
                        </div>

                        <!-- Sizes & Icons -->
                        <div>
                            <p class="text-sm font-medium text-muted-foreground mb-4">Sizes & Icons</p>
                            <div class="flex flex-wrap items-center gap-4">
                                <x-tyro-dashboard::button size="sm">Small</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button size="default">Default</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button size="lg">Large</x-tyro-dashboard::button>
                                <x-tyro-dashboard::button icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>'>
                                    With Icon
                                </x-tyro-dashboard::button>
                                <x-tyro-dashboard::button size="icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </x-tyro-dashboard::button>
                                <x-tyro-dashboard::button loading>Loading...</x-tyro-dashboard::button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Code Block -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Code Block</h3>
                    <x-tyro-dashboard::codeblock language="php">
function hello($name) {
    return "Hello, {$name}!";
}

echo hello("World");
                    </x-tyro-dashboard::codeblock>
                </div>

                <!-- Gallery -->
                <div class="mb-12">
                    <h3 class="text-xl font-bold border-b pb-2 mb-8">Premium Photo Galleries</h3>

                    @php
                    $galleryImages = [
                    ['url' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', 'alt' => 'Yosemite Valley', 'caption' => 'A breathtaking view of Yosemite Valley at sunset.'],
                    ['url' => 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=800&q=80', 'alt' => 'Swiss Alps', 'caption' => 'The majestic peaks of the Swiss Alps reflecting in a mirror lake.'],
                    ['url' => 'https://images.unsplash.com/photo-1433838552652-f9a46b332c40?auto=format&fit=crop&w=800&q=80', 'alt' => 'Hot Air Balloons', 'caption' => 'Colorful hot air balloons floating over Cappadocia.'],
                    ['url' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=800&q=80', 'alt' => 'Lake Braies', 'caption' => 'Wooden boats on the crystal clear waters of Lake Braies, Italy.'],
                    ['url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=800&q=80', 'alt' => 'Autumn Forest', 'caption' => 'Sunlight filtering through golden autumn leaves in a dense forest.'],
                    ['url' => 'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?auto=format&fit=crop&w=800&q=80', 'alt' => 'Tropical Paradise', 'caption' => 'Pristine white sand beach with turquoise water and palm trees.'],
                    ];
                    @endphp

                    <div class="space-y-12">
                        <!-- Basic Grid -->
                        <div>
                            <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Modern Grid (3 Columns)</p>
                            <x-tyro-dashboard::gallery :images="$galleryImages" :columns="3" gap="6" ratio="square" showCaptions />
                        </div>

                        <!-- Visual Variants -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <!-- Video Ratio -->
                            <div>
                                <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Cinematic Ratio (16:9)</p>
                                <x-tyro-dashboard::gallery :images="array_slice($galleryImages, 0, 4)" :columns="2" gap="4" ratio="video" />
                            </div>

                            <!-- Portrait Ratio -->
                            <div>
                                <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Elegant Portrait (3:4)</p>
                                <x-tyro-dashboard::gallery :images="array_slice($galleryImages, 2, 4)" :columns="2" gap="4" ratio="portrait" />
                            </div>
                        </div>

                        <!-- Wide Layout -->
                        <div>
                            <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Wide Layout (4 Columns, Small Gap)</p>
                            <x-tyro-dashboard::gallery :images="$galleryImages" :columns="4" gap="3" ratio="wide" />
                        </div>
                    </div>
                </div>
        </section>

        <!-- Premium Image Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Premium Image Components</h2>

            <div class="space-y-12">
                <!-- Hover Effects & Captions -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Hover Effects & Captions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <x-tyro-dashboard::image src="https://picsum.photos/800/600?random=10" hover="zoom" caption="Zoom Effect on Hover" rounded="xl" shadow="lg" />
                        <x-tyro-dashboard::image src="https://picsum.photos/800/600?random=11" hover="lift" caption="Lift & Shadow Effect" rounded="xl" shadow="md" />
                        <x-tyro-dashboard::image src="https://picsum.photos/800/600?random=12" hover="grayscale" caption="Grayscale to Color" rounded="xl" />
                        <x-tyro-dashboard::image src="https://picsum.photos/800/600?random=13" hover="blur" caption="Focus on Hover" rounded="xl" />
                    </div>
                </div>

                <!-- Aspect Ratios & Shadows -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Aspect Ratios & Glow Shadows</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <p class="text-xs font-bold text-muted-foreground mb-3">Square (1:1) with Glow</p>
                            <x-tyro-dashboard::image src="https://picsum.photos/600/600?random=14" aspect="square" shadow="glow" rounded="full" border="white" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-muted-foreground mb-3">Video (16:9) XL Shadow</p>
                            <x-tyro-dashboard::image src="https://picsum.photos/800/450?random=15" aspect="video" shadow="xl" rounded="lg" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-muted-foreground mb-3">Portrait (3:4)</p>
                            <x-tyro-dashboard::image src="https://picsum.photos/450/600?random=16" aspect="portrait" shadow="lg" rounded="2xl" />
                        </div>
                    </div>
                </div>

                <!-- Filter Effects -->
                <div x-data="{ 
                    selectedFilter: 'none',
                    selectedIntensity: 'medium',
                    filters: [
                        { value: 'none', label: 'None (Original)' },
                        { value: 'blur', label: 'Blur' },
                        { value: 'grayscale', label: 'Grayscale' },
                        { value: 'sepia', label: 'Sepia' },
                        { value: 'brightness', label: 'Brightness' },
                        { value: 'contrast', label: 'Contrast' },
                        { value: 'saturate', label: 'Saturate' },
                        { value: 'hue-rotate', label: 'Hue Rotate' },
                        { value: 'invert', label: 'Invert' },
                        { value: 'crystal', label: 'Crystal (Preset)' },
                        { value: 'vintage', label: 'Vintage (Preset)' },
                        { value: 'warm', label: 'Warm (Preset)' },
                        { value: 'cool', label: 'Cool (Preset)' }
                    ],
                    intensities: [
                        { value: 'light', label: 'Light' },
                        { value: 'medium', label: 'Medium' },
                        { value: 'heavy', label: 'Heavy' }
                    ]
                }">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Image Filters</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium mb-2">Select Filter</label>
                            <select x-model="selectedFilter" class="w-full px-4 py-2 border rounded-lg bg-white">
                                <template x-for="filter in filters" :key="filter.value">
                                    <option :value="filter.value" x-text="filter.label"></option>
                                </template>
                            </select>
                        </div>
                        <div x-show="selectedFilter !== 'none' && !['crystal', 'vintage', 'warm', 'cool'].includes(selectedFilter)">
                            <label class="block text-sm font-medium mb-2">Filter Intensity</label>
                            <select x-model="selectedIntensity" class="w-full px-4 py-2 border rounded-lg bg-white">
                                <template x-for="intensity in intensities" :key="intensity.value">
                                    <option :value="intensity.value" x-text="intensity.label"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold">Preview</p>
                                <span class="text-xs text-muted-foreground px-3 py-1 bg-muted rounded-full" x-text="selectedFilter === 'none' ? 'No Filter' : filters.find(f => f.value === selectedFilter)?.label + (selectedIntensity && !['crystal', 'vintage', 'warm', 'cool'].includes(selectedFilter) ? ' (' + selectedIntensity.charAt(0).toUpperCase() + selectedIntensity.slice(1) + ')' : '')"></span>
                            </div>
                            <div class="aspect-video">
                                <img src="https://picsum.photos/800/600?random=20" alt="Filter preview" class="w-full h-full object-cover rounded-lg shadow-lg" :style="selectedFilter === 'none' ? '' : getFilterStyle(selectedFilter, selectedIntensity)" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <p class="text-sm font-semibold">Usage Example</p>
                            <div class="bg-muted p-4 rounded-lg">
                                <pre class="text-xs overflow-x-auto"><code x-text="getCodeExample(selectedFilter, selectedIntensity)"></code></pre>
                            </div>
                            <div class="text-xs text-muted-foreground space-y-2 mt-4">
                                <p><strong>Filter Options:</strong></p>
                                <ul class="list-disc list-inside space-y-1 ml-2">
                                    <li>Basic filters: blur, grayscale, sepia, brightness, contrast, saturate, hue-rotate, invert</li>
                                    <li>Preset combinations: crystal, vintage, warm, cool</li>
                                    <li>Intensity levels: light, medium, heavy</li>
                                    <li>Custom values: Use specific CSS values like '5px', '75%', '1.5', etc.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function getFilterStyle(filter, intensity) {
                        if (['crystal', 'vintage', 'warm', 'cool'].includes(filter)) {
                            const presets = {
                                crystal: 'contrast(1.2) brightness(1.1) saturate(1.2)',
                                vintage: 'sepia(50%) contrast(1.2) brightness(0.9)',
                                warm: 'sepia(30%) saturate(1.3) hue-rotate(-10deg)',
                                cool: 'saturate(1.2) hue-rotate(10deg) brightness(1.05)'
                            };
                            return `filter: ${presets[filter]}`;
                        }

                        const values = {
                            light: {
                                blur: '2px',
                                grayscale: '50%',
                                sepia: '40%',
                                brightness: '1.15',
                                contrast: '1.2',
                                saturate: '1.3',
                                'hue-rotate': '45deg',
                                invert: '50%'
                            },
                            medium: {
                                blur: '4px',
                                grayscale: '75%',
                                sepia: '60%',
                                brightness: '1.25',
                                contrast: '1.4',
                                saturate: '1.6',
                                'hue-rotate': '90deg',
                                invert: '75%'
                            },
                            heavy: {
                                blur: '8px',
                                grayscale: '100%',
                                sepia: '100%',
                                brightness: '1.5',
                                contrast: '1.8',
                                saturate: '2.5',
                                'hue-rotate': '180deg',
                                invert: '100%'
                            }
                        };

                        const value = values[intensity][filter];
                        return `filter: ${filter}(${value})`;
                    }

                    function getCodeExample(filter, intensity) {
                        if (filter === 'none') {
                            return '<' + `x-tyro-dashboard::image 
    src="path/to/image.jpg" 
    rounded="lg"
    shadow="md"
/>`;
                        }

                        if (['crystal', 'vintage', 'warm', 'cool'].includes(filter)) {
                            return '<' + `x-tyro-dashboard::image 
    src="path/to/image.jpg" 
    filter="${filter}"
    rounded="lg"
    shadow="md"
/>`;
                        }

                        return '<' + `x-tyro-dashboard::image 
    src="path/to/image.jpg" 
    filter="${filter}"
    filterAmount="${intensity}"
    rounded="lg"
    shadow="md"
/>`;
                    }
                </script>
            </div>
        </section>

        <!-- Additional Components Section 1 -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">UI Elements</h2>

            <!-- Badges -->
            <div class="mb-12">
                <h3 class="text-lg font-semibold mb-6">Badges</h3>

                <div class="space-y-8">
                    <!-- Soft Badges -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Soft Variant (Default)</p>
                        <div class="flex flex-wrap gap-3">
                            <x-tyro-dashboard::badge>Default</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="success">Success</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="warning">Warning</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="info">Info</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="destructive">Destructive</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="secondary">Secondary</x-tyro-dashboard::badge>
                        </div>
                    </div>

                    <!-- Solid Badges -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Solid Variant</p>
                        <div class="flex flex-wrap gap-3">
                            <x-tyro-dashboard::badge variant="solid">Default</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="solid" color="success">Success</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="solid" color="warning">Warning</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="solid" color="info">Info</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="solid" color="destructive">Destructive</x-tyro-dashboard::badge>
                        </div>
                    </div>

                    <!-- Outline Badges -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Outline Variant</p>
                        <div class="flex flex-wrap gap-3">
                            <x-tyro-dashboard::badge variant="outline">Default</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="outline" color="success">Success</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="outline" color="warning">Warning</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="outline" color="info">Info</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge variant="outline" color="destructive">Destructive</x-tyro-dashboard::badge>
                        </div>
                    </div>

                    <!-- Custom Colors -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">All Supported Colors</p>
                        <div class="flex flex-wrap gap-3">
                            <x-tyro-dashboard::badge color="red">Red</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="blue">Blue</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="green">Green</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="yellow">Yellow</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="pink">Pink</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="cyan">Cyan</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="gray">Gray</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="purple">Purple</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge color="orange">Orange</x-tyro-dashboard::badge>
                        </div>
                    </div>

                    <!-- Badges with Dots -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Badges with Dot Indicators</p>
                        <div class="flex flex-wrap gap-3">
                            <x-tyro-dashboard::badge dot>Pending</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge dot color="success">Active</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge dot color="warning">Review</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge dot color="destructive">Error</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge dot variant="outline" color="blue">System</x-tyro-dashboard::badge>
                        </div>
                    </div>

                    <!-- Sizing and Rounding -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Sizing and Rounding</p>
                        <div class="flex items-center flex-wrap gap-3">
                            <x-tyro-dashboard::badge size="sm">Small</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge>Default</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge size="lg">Large</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge rounded="none">Square</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge rounded="sm">Rounded SM</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge rounded="md">Rounded MD</x-tyro-dashboard::badge>
                            <x-tyro-dashboard::badge rounded="lg">Rounded LG</x-tyro-dashboard::badge>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="mb-12">
                <h3 class="text-lg font-semibold mb-6">Premium Alerts</h3>

                <div class="space-y-8">
                    <!-- Soft Alerts (Default) -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Soft Variant (Default)</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-tyro-dashboard::alert type="info" title="Information">
                                This is a subtle informational alert message.
                            </x-tyro-dashboard::alert>

                            <x-tyro-dashboard::alert type="success" title="Success!" dismissible>
                                Your changes have been saved successfully.
                            </x-tyro-dashboard::alert>

                            <x-tyro-dashboard::alert type="warning" title="Warning" accent>
                                Please review your information before proceeding.
                            </x-tyro-dashboard::alert>

                            <x-tyro-dashboard::alert type="error" title="Critical Error" dismissible accent>
                                An error occurred while processing your request.
                            </x-tyro-dashboard::alert>
                        </div>
                    </div>

                    <!-- Solid Alerts -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Solid Variant</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-tyro-dashboard::alert variant="solid" type="primary" title="Update Available">
                                A new version of the dashboard is available for download.
                            </x-tyro-dashboard::alert>

                            <x-tyro-dashboard::alert variant="solid" type="success" title="Payment Received" dismissible>
                                We've received your payment and processed your order.
                            </x-tyro-dashboard::alert>
                        </div>
                    </div>

                    <!-- Outline -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Outline Variant</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-tyro-dashboard::alert variant="outline" type="info" title="Note">
                                This alert uses an outline style for secondary information.
                            </x-tyro-dashboard::alert>
                        </div>
                    </div>

                    <!-- Custom Colors & Icons -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-4">Custom Colors & Professional Icons</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-tyro-dashboard::alert color="purple" title="New Badge" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>'>
                                You've unlocked a new achievement!
                            </x-tyro-dashboard::alert>

                            <x-tyro-dashboard::alert color="orange" title="Storage" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>'>
                                Your storage is almost full (92%).
                            </x-tyro-dashboard::alert>

                            <x-tyro-dashboard::alert color="cyan" title="System" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>'>
                                system performance is optimal.
                            </x-tyro-dashboard::alert>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bars -->
            <div class="mb-12">
                <h3 class="text-xl font-bold border-b pb-2 mb-8">Premium Progress Bars</h3>

                <div class="space-y-12">
                    <!-- Sizes -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Available Sizes</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <x-tyro-dashboard::progress :value="30" size="xs" showLabel labelPosition="outside">XS Bar</x-tyro-dashboard::progress>
                                <x-tyro-dashboard::progress :value="45" size="sm" showLabel labelPosition="outside">SM Bar</x-tyro-dashboard::progress>
                                <x-tyro-dashboard::progress :value="60" size="default" showLabel labelPosition="outside">Default Bar</x-tyro-dashboard::progress>
                            </div>
                            <div class="space-y-4">
                                <x-tyro-dashboard::progress :value="75" size="lg" showLabel labelPosition="outside">LG Bar</x-tyro-dashboard::progress>
                                <x-tyro-dashboard::progress :value="90" size="xl" showLabel labelPosition="outside">XL Bar</x-tyro-dashboard::progress>
                            </div>
                        </div>
                    </div>

                    <!-- Variants & Colors -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Variants & Professional Colors</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Solid Colors -->
                            <div class="space-y-4">
                                <p class="text-xs font-bold text-muted-foreground">Solid (Default)</p>
                                <x-tyro-dashboard::progress :value="80" color="primary" />
                                <x-tyro-dashboard::progress :value="65" color="success" />
                                <x-tyro-dashboard::progress :value="40" color="warning" />
                                <x-tyro-dashboard::progress :value="20" color="red" />
                            </div>

                            <!-- Soft Variants -->
                            <div class="space-y-4">
                                <p class="text-xs font-bold text-muted-foreground">Soft Backgrounds</p>
                                <x-tyro-dashboard::progress :value="80" color="blue" variant="soft" />
                                <x-tyro-dashboard::progress :value="65" color="purple" variant="soft" />
                                <x-tyro-dashboard::progress :value="45" color="orange" variant="soft" />
                                <x-tyro-dashboard::progress :value="30" color="cyan" variant="soft" />
                            </div>

                            <!-- Gradient Variants -->
                            <div class="space-y-4">
                                <p class="text-xs font-bold text-muted-foreground">Modern Gradients</p>
                                <x-tyro-dashboard::progress :value="90" color="indigo" variant="gradient" />
                                <x-tyro-dashboard::progress :value="75" color="pink" variant="gradient" />
                                <x-tyro-dashboard::progress :value="55" color="teal" variant="gradient" />
                                <x-tyro-dashboard::progress :value="40" color="amber" variant="gradient" />
                                <x-tyro-dashboard::progress :value="25" color="red" variant="gradient" />
                            </div>
                        </div>
                    </div>

                    <!-- Animations -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Dynamic Animations</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <p class="text-xs font-bold text-muted-foreground mb-2">Animated Stripes (Active)</p>
                                    <x-tyro-dashboard::progress :value="65" color="primary" striped animated />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-muted-foreground mb-2">Pulsing Effect (Standby)</p>
                                    <x-tyro-dashboard::progress :value="45" color="success" pulse />
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <p class="text-xs font-bold text-muted-foreground mb-2">Indeterminate State (Loading)</p>
                                    <x-tyro-dashboard::progress indeterminate color="info" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-muted-foreground mb-2">Complex Combination</p>
                                    <x-tyro-dashboard::progress :value="85" color="purple" variant="gradient" striped animated />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Labels -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Label Placements</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <x-tyro-dashboard::progress :value="65" size="lg" showLabel labelPosition="inside" color="primary">
                                    Label Inside (LG/XL)
                                </x-tyro-dashboard::progress>
                                <x-tyro-dashboard::progress :value="45" size="xl" showLabel labelPosition="inside" color="success" striped animated>
                                    Uploading Files...
                                </x-tyro-dashboard::progress>
                            </div>
                            <div class="space-y-6">
                                <x-tyro-dashboard::progress :value="75" showLabel labelPosition="outside" color="warning">
                                    Label Outside
                                </x-tyro-dashboard::progress>
                                <x-tyro-dashboard::progress :value="25" size="xs" showLabel labelPosition="outside" color="destructive">
                                    Thinner Bar with Label
                                </x-tyro-dashboard::progress>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loader -->
            <div class="mb-12">
                <h3 class="text-xl font-bold border-b pb-2 mb-8">Premium Loaders</h3>

                <div class="space-y-12">
                    <!-- Types -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Loader Types</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 items-center bg-muted/20 p-8 rounded-xl border border-dashed border-border">
                            <div class="flex flex-col items-center gap-3">
                                <x-tyro-dashboard::loader type="spinner" size="lg" />
                                <span class="text-[10px] font-bold uppercase text-muted-foreground">Spinner</span>
                            </div>
                            <div class="flex flex-col items-center gap-3">
                                <x-tyro-dashboard::loader type="ring" size="lg" />
                                <span class="text-[10px] font-bold uppercase text-muted-foreground">Ring</span>
                            </div>
                            <div class="flex flex-col items-center gap-3">
                                <x-tyro-dashboard::loader type="bars" size="lg" />
                                <span class="text-[10px] font-bold uppercase text-muted-foreground">Bars</span>
                            </div>
                            <div class="flex flex-col items-center gap-3">
                                <x-tyro-dashboard::loader type="dots" size="lg" />
                                <span class="text-[10px] font-bold uppercase text-muted-foreground">Dots</span>
                            </div>
                            <div class="flex flex-col items-center gap-3">
                                <x-tyro-dashboard::loader type="pulse" size="lg" />
                                <span class="text-[10px] font-bold uppercase text-muted-foreground">Pulse</span>
                            </div>
                            <div class="flex flex-col items-center gap-3">
                                <x-tyro-dashboard::loader type="progress" variant="info" text="Loading..." />
                                <span class="text-[10px] font-bold uppercase text-muted-foreground">Progress</span>
                            </div>
                        </div>
                    </div>

                    <!-- Colors -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Professional Colors</p>
                        <div class="flex flex-wrap gap-10 items-center justify-center p-8 bg-background rounded-xl border border-border">
                            <x-tyro-dashboard::loader variant="primary" type="spinner" />
                            <x-tyro-dashboard::loader variant="success" type="ring" />
                            <x-tyro-dashboard::loader variant="warning" type="bars" />
                            <x-tyro-dashboard::loader variant="destructive" type="dots" />
                            <x-tyro-dashboard::loader variant="info" type="pulse" />
                            <x-tyro-dashboard::loader variant="purple" type="spinner" />
                            <x-tyro-dashboard::loader variant="orange" type="ring" />
                            <x-tyro-dashboard::loader variant="cyan" type="bars" />
                            <x-tyro-dashboard::loader variant="pink" type="dots" />
                        </div>
                    </div>

                    <!-- Sizes & Text -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Sizes & Labels</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <div class="space-y-8">
                                <p class="text-xs font-bold text-muted-foreground">Available Sizes</p>
                                <div class="flex flex-wrap items-end gap-6">
                                    <div class="text-center">
                                        <x-tyro-dashboard::loader size="xs" />
                                        <p class="text-[10px] mt-2 font-medium">XS</p>
                                    </div>
                                    <div class="text-center">
                                        <x-tyro-dashboard::loader size="sm" />
                                        <p class="text-[10px] mt-2 font-medium">SM</p>
                                    </div>
                                    <div class="text-center">
                                        <x-tyro-dashboard::loader size="default" />
                                        <p class="text-[10px] mt-2 font-medium">Default</p>
                                    </div>
                                    <div class="text-center">
                                        <x-tyro-dashboard::loader size="lg" />
                                        <p class="text-[10px] mt-2 font-medium">LG</p>
                                    </div>
                                    <div class="text-center">
                                        <x-tyro-dashboard::loader size="xl" />
                                        <p class="text-[10px] mt-2 font-medium">XL</p>
                                    </div>
                                    <div class="text-center">
                                        <x-tyro-dashboard::loader size="2xl" />
                                        <p class="text-[10px] mt-2 font-medium">2XL</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-8">
                                <p class="text-xs font-bold text-muted-foreground">Loaders with Labels</p>
                                <div class="flex flex-wrap gap-8 items-center">
                                    <x-tyro-dashboard::loader size="lg" variant="primary" text="Working on it..." />
                                    <x-tyro-dashboard::loader size="sm" variant="success" text="Almost there" />
                                    <x-tyro-dashboard::loader type="dots" variant="info" text="Synchronizing" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Dividers</h3>
                <x-tyro-dashboard::divider />
                <x-tyro-dashboard::divider text="OR" class="my-6" />
                <x-tyro-dashboard::divider textAlign="left">
                    Continue with
                </x-tyro-dashboard::divider>
            </div>
        </section>

        <!-- Additional Components Section 2 -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Navigation Components</h2>

            <!-- Breadcrumbs -->
            <div class="mb-12">
                <h3 class="text-xl font-bold border-b pb-2 mb-8">Premium Breadcrumbs</h3>

                <div class="space-y-12">
                    <!-- Basic & Icons -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Ghost Variant & Custom Icons</p>
                        @php
                        $items = [
                        ['label' => 'Home', 'url' => '#', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>'],
                        ['label' => 'Dashboard', 'url' => '#'],
                        ['label' => 'Settings', 'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>']
                        ];
                        @endphp
                        <x-tyro-dashboard::breadcrumb :items="$items" separator="chevron" />
                    </div>

                    <!-- Soft Variants -->
                    <div class="space-y-6">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Soft Variants</p>
                        <div class="flex flex-col gap-6">
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Store', 'url' => '#'], ['label' => 'Catalog', 'url' => '#'], ['label' => 'Men\'s Shoes']]" variant="soft" color="primary" separator="arrow" />
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Analytics', 'url' => '#'], ['label' => 'Reports', 'url' => '#'], ['label' => 'Real-time']]" variant="soft" color="success" separator="slash" />
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Admin', 'url' => '#'], ['label' => 'Security', 'url' => '#'], ['label' => 'Firewall']]" variant="soft" color="destructive" separator="dot" />
                        </div>
                    </div>

                    <!-- Solid Variants -->
                    <div class="space-y-6">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Solid Color Backgrounds</p>
                        <div class="flex flex-col gap-6">
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Projects', 'url' => '#'], ['label' => 'Frontend', 'url' => '#'], ['label' => 'Layout']]" variant="solid" color="info" separator="arrow" />
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Documentation', 'url' => '#'], ['label' => 'API', 'url' => '#'], ['label' => 'V1.2']]" variant="solid" color="purple" separator="chevron" />
                        </div>
                    </div>

                    <!-- Outline & Sizes -->
                    <div class="space-y-6">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Outline Variant & Sizing</p>
                        <div class="flex flex-col gap-6">
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Library', 'url' => '#'], ['label' => 'Books', 'url' => '#'], ['label' => 'Fiction']]" variant="outline" color="blue" size="sm" />
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Library', 'url' => '#'], ['label' => 'Books', 'url' => '#'], ['label' => 'History']]" variant="outline" color="orange" size="default" />
                            <x-tyro-dashboard::breadcrumb :items="[['label' => 'Library', 'url' => '#'], ['label' => 'Books', 'url' => '#'], ['label' => 'Science']]" variant="outline" color="cyan" size="lg" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-6">Dropdown Menus</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Standard Menu -->
                    <div class="space-y-4">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Standard Menu</p>
                        <x-tyro-dashboard::menu label="Options">
                            <x-tyro-dashboard::menu-item label="Edit Profile" />
                            <x-tyro-dashboard::menu-item label="Account Settings" />
                            <x-tyro-dashboard::menu-item type="divider" />
                            <x-tyro-dashboard::menu-item label="Logout" destructive />
                        </x-tyro-dashboard::menu>
                    </div>

                    <!-- Menu with Icons & Shortcuts -->
                    <div class="space-y-4">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Icons & Shortcuts</p>
                        <x-tyro-dashboard::menu label="Actions" color="info" variant="soft">
                            <x-tyro-dashboard::menu-item label="New Project" shortcut="⌘N" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>' />
                            <x-tyro-dashboard::menu-item label="Download JSON" shortcut="⌘D" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>' />
                            <x-tyro-dashboard::menu-item type="divider" />
                            <x-tyro-dashboard::menu-item label="Archived Items" disabled icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>' />
                        </x-tyro-dashboard::menu>
                    </div>

                    <!-- Complex Menu with Groups -->
                    <div class="space-y-4">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Groups & Headers</p>
                        <x-tyro-dashboard::menu label="Manage Content" color="purple" align="right">
                            <x-tyro-dashboard::menu-item type="header" label="User Content" />
                            <x-tyro-dashboard::menu-item label="Posts" />
                            <x-tyro-dashboard::menu-item label="Comments" />
                            <x-tyro-dashboard::menu-item type="divider" />
                            <x-tyro-dashboard::menu-item type="header" label="Administration" />
                            <x-tyro-dashboard::menu-item label="Categories" />
                            <x-tyro-dashboard::menu-item label="Tags" />
                            <x-tyro-dashboard::menu-item type="divider" />
                            <x-tyro-dashboard::menu-item label="Danger Zone" destructive />
                        </x-tyro-dashboard::menu>
                    </div>

                    <!-- Custom Trigger (Avatar) -->
                    <div class="space-y-4">
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider">Custom Trigger</p>
                        <x-tyro-dashboard::menu align="right">
                            <x-slot name="trigger">
                                <x-tyro-dashboard::button variant="ghost" class="flex items-center gap-2 rounded-full p-1 pr-3">
                                    <x-tyro-dashboard::gravatar email="me@hasin.me" size="32" rounded="full" />
                                    <span class="text-sm font-medium">Hasin</span>
                                    <svg class="h-4 w-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </x-tyro-dashboard::button>
                            </x-slot>
                            <x-tyro-dashboard::menu-item label="My Profile" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' />
                            <x-tyro-dashboard::menu-item label="Subscription" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>' />
                            <x-tyro-dashboard::menu-item type="divider" />
                            <x-tyro-dashboard::menu-item label="Logout" destructive icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>' />
                        </x-tyro-dashboard::menu>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modern Color Picker Section -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Modern Color Picker</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <x-tyro-dashboard::colorpicker name="primary_color" label="Brand Primary" value="#3b82f6" help="Pick your brand's main color" />

                <x-tyro-dashboard::colorpicker name="accent_color" label="Accent Color" value="#8b5cf6" help="Pick an accent color for UI elements" />

                <x-tyro-dashboard::colorpicker name="bg_color" label="Background Color" value="#ffffff" help="Customize your dashboard workspace" />
            </div>
        </section>
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Advanced Inputs</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <x-tyro-dashboard::timezone name="timezone" label="Timezone" value="Asia/Dhaka" />

                <x-tyro-dashboard::country name="user_country" label="Country" value="BD" />

                <x-tyro-dashboard::currency name="currency" label="Currency" value="BDT" />
            </div>
        </section>

        <!-- Media Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Media Components</h2>

            <!-- Image -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Image</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-tyro-dashboard::image src="https://picsum.photos/300/200?random=10" alt="Sample image" rounded="md" />
                    <x-tyro-dashboard::image src="https://picsum.photos/300/200?random=11" alt="Sample image" rounded="lg" />
                    <x-tyro-dashboard::image src="https://picsum.photos/300/200?random=12" alt="Sample image" rounded="full" width="200px" height="200px" objectFit="cover" />
                </div>
            </div>

            <!-- YouTube -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">YouTube Video</h3>
                <x-tyro-dashboard::youtube videoId="7S_tz1z_5bA" title="Sample YouTube Video" />
            </div>

            <!-- Simple Map (Iframe) -->
            <div class="mb-12">
                <h3 class="text-xl font-bold mb-8 border-b pb-2">Simple Maps (Iframe - No API Key Required)</h3>
                <p class="text-sm text-muted-foreground mb-6">Lightweight iframe-based maps that work without API configuration. Perfect for simple location displays.</p>
                
                <div class="space-y-12">
                    <!-- Location Name -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Maps with Location Names</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-tyro-dashboard::map 
                                location="Eiffel Tower, Paris, France"
                                title="Eiffel Tower"
                                help="Using location name directly"
                                height="350px"
                            />

                            <x-tyro-dashboard::map 
                                location="Times Square, New York, USA"
                                :zoom="15"
                                title="Times Square"
                                help="Location name with custom zoom"
                                height="350px"
                            />
                        </div>
                    </div>

                    <!-- Coordinates -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Maps with Coordinates</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-tyro-dashboard::map 
                                :lat="23.8103" 
                                :lon="90.4125"
                                title="Dhaka, Bangladesh"
                                help="Using latitude and longitude"
                                height="350px"
                            />

                            <x-tyro-dashboard::map 
                                :lat="-33.8568" 
                                :lon="151.2153"
                                :zoom="16"
                                mapType="satellite"
                                title="Sydney Opera House"
                                help="Coordinates with satellite view"
                                height="350px"
                            />
                        </div>
                    </div>

                    <!-- Different Styles -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Different Sizes & Styles</p>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <x-tyro-dashboard::map 
                                location="Golden Gate Bridge, San Francisco"
                                height="250px"
                                rounded="sm"
                                title="Compact Map"
                            />

                            <x-tyro-dashboard::map 
                                location="Colosseum, Rome, Italy"
                                height="250px"
                                rounded="xl"
                                title="Rounded Map"
                            />

                            <x-tyro-dashboard::map 
                                location="Burj Khalifa, Dubai"
                                height="250px"
                                :bordered="false"
                                title="No Border"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Google Map -->
            <div class="mb-12">
                <h3 class="text-xl font-bold mb-8 border-b pb-2">Advanced Google Maps (Requires API Key)</h3>
                <p class="text-sm text-muted-foreground mb-6">Feature-rich interactive maps with custom markers, styles, and info windows. Requires Google Maps JavaScript API key.</p>
                
                @php
                $cityMarkers = [
                    ['lat' => 40.7128, 'lon' => -74.0060, 'label' => 'New York', 'color' => 'FF6B6B', 'info' => 'The Big Apple - Financial and cultural capital'],
                    ['lat' => 34.0522, 'lon' => -118.2437, 'label' => 'Los Angeles', 'color' => '4ECDC4', 'info' => 'City of Angels - Entertainment capital of the world'],
                    ['lat' => 41.8781, 'lon' => -87.6298, 'label' => 'Chicago', 'color' => '45B7D1', 'info' => 'The Windy City - Known for architecture and deep-dish pizza'],
                    ['lat' => 29.7604, 'lon' => -95.3698, 'label' => 'Houston', 'color' => 'F7B731', 'info' => 'Space City - Home to NASA Johnson Space Center'],
                ];

                $restaurantMarkers = [
                    ['lat' => 37.7749, 'lon' => -122.4194, 'label' => 'The French Laundry', 'color' => 'E74C3C', 'info' => 'Michelin 3-star restaurant in Napa Valley'],
                    ['lat' => 37.7849, 'lon' => -122.4094, 'label' => 'Chez Panisse', 'color' => 'E74C3C', 'info' => 'Legendary Berkeley restaurant'],
                    ['lat' => 37.7649, 'lon' => -122.4294, 'label' => 'Zuni Café', 'color' => 'E74C3C', 'info' => 'Iconic San Francisco eatery'],
                ];
                @endphp

                <div class="space-y-12">
                    <!-- Basic Examples -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Basic Maps</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <x-tyro-dashboard::google-map 
                                :lat="23.8103" 
                                :lon="90.4125" 
                                title="Dhaka, Bangladesh" 
                                help="Simple map with latitude/longitude coordinates"
                                height="350px"
                            />

                            <x-tyro-dashboard::google-map 
                                :lat="51.5074" 
                                :lon="-0.1278" 
                                :zoom="12"
                                mapType="satellite"
                                title="London Satellite View" 
                                help="Satellite view of London with custom zoom level"
                                height="350px"
                            />
                        </div>
                    </div>

                    <!-- Single Marker -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Single Marker with Info Window</p>
                        <x-tyro-dashboard::google-map 
                            :lat="48.8584" 
                            :lon="2.2945"
                            :zoom="15"
                            :markers="[
                                ['lat' => 48.8584, 'lon' => 2.2945, 'label' => 'Eiffel Tower', 'color' => '3498db', 'info' => 'Iconic iron lattice tower built in 1889. Click to see info!']
                            ]"
                            title="Eiffel Tower"
                            help="Click the marker to see the info window"
                            height="400px"
                        />
                    </div>

                    <!-- Multiple Markers -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Multiple Markers with Auto-Fit Bounds</p>
                        <x-tyro-dashboard::google-map 
                            :markers="$cityMarkers"
                            title="Major US Cities"
                            help="Map automatically adjusts to show all markers. Click markers for details!"
                            height="450px"
                        />
                    </div>

                    <!-- Map Styles -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Premium Map Styles</p>
                        <p class="text-sm text-muted-foreground mb-6">Each style provides a unique visual theme. Note: Custom styles cannot be used with colored markers.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Default Style</p>
                                <x-tyro-dashboard::google-map 
                                    :lat="48.8566" 
                                    :lon="2.3522"
                                    :zoom="13"
                                    style="default"
                                    title="Paris - Default"
                                    help="Standard Google Maps appearance"
                                    height="300px"
                                />
                            </div>

                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Silver Style</p>
                                <x-tyro-dashboard::google-map 
                                    :lat="51.5074" 
                                    :lon="-0.1278"
                                    :zoom="13"
                                    style="silver"
                                    title="London - Silver"
                                    help="Elegant grayscale theme"
                                    height="300px"
                                />
                            </div>

                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Dark Style</p>
                                <x-tyro-dashboard::google-map 
                                    :lat="40.7128" 
                                    :lon="-74.0060"
                                    :zoom="13"
                                    style="dark"
                                    title="New York - Dark"
                                    help="Modern dark theme for night mode"
                                    height="300px"
                                />
                            </div>

                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Retro Style</p>
                                <x-tyro-dashboard::google-map 
                                    :lat="37.7749" 
                                    :lon="-122.4194"
                                    :zoom="13"
                                    style="retro"
                                    title="San Francisco - Retro"
                                    help="Vintage map aesthetic with muted colors"
                                    height="300px"
                                />
                            </div>

                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Night Style</p>
                                <x-tyro-dashboard::google-map 
                                    :lat="35.6762" 
                                    :lon="139.6503"
                                    :zoom="13"
                                    style="night"
                                    title="Tokyo - Night"
                                    help="Atmospheric night theme with rich colors"
                                    height="300px"
                                />
                            </div>

                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Terrain Type</p>
                                <x-tyro-dashboard::google-map 
                                    :lat="27.9881" 
                                    :lon="86.9250"
                                    :zoom="10"
                                    mapType="terrain"
                                    title="Mount Everest"
                                    help="Topographical view with elevation details"
                                    height="300px"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Features -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Advanced Features</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Hybrid Map Type with Markers</p>
                                <x-tyro-dashboard::google-map 
                                    :markers="$restaurantMarkers"
                                    mapType="hybrid"
                                    style="dark"
                                    title="San Francisco Bay Area Restaurants"
                                    help="Hybrid view combines satellite imagery with road labels"
                                    height="350px"
                                />
                            </div>

                            <div>
                                <p class="text-xs font-semibold mb-3 text-muted-foreground">Minimal Controls</p>
                                <x-tyro-dashboard::google-map 
                                    :lat="-33.8688" 
                                    :lon="151.2093"
                                    :zoom="14"
                                    :showControls="false"
                                    :showTypeControl="false"
                                    :draggable="false"
                                    :scrollwheel="false"
                                    :markers="[
                                        ['lat' => -33.8568, 'lon' => 151.2153, 'label' => 'Sydney Opera House', 'color' => '9b59b6', 'info' => 'World-famous performing arts center']
                                    ]"
                                    title="Sydney Opera House"
                                    help="Map with minimal controls and interactions disabled"
                                    height="350px"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avatars & Gravatars -->
            <div class="mb-12">
                <h3 class="text-xl font-bold mb-8 border-b pb-2">Avatars & Gravatars</h3>

                <div class="space-y-12">
                    <!-- Sizes & Shapes -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-6 uppercase tracking-wider">Sizes & Shapes</p>
                        <div class="flex flex-wrap items-end gap-6">
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="xs" />
                                <p class="text-[10px] text-muted-foreground uppercase font-bold">XS</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="sm" />
                                <p class="text-[10px] text-muted-foreground uppercase font-bold">SM</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="md" />
                                <p class="text-[10px] text-muted-foreground uppercase font-bold">MD</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="lg" />
                                <p class="text-[10px] text-muted-foreground uppercase font-bold">LG</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="xl" />
                                <p class="text-[10px] text-muted-foreground uppercase font-bold">XL</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="2xl" rounded="rounded" />
                                <p class="text-[10px] text-muted-foreground uppercase font-bold">2XL Rounded</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Indicators -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-6 uppercase tracking-wider">Status Indicators</p>
                        <div class="flex flex-wrap gap-8">
                            <div class="flex items-center gap-3">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="lg" status="online" />
                                <span class="text-sm font-medium">Online</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="lg" status="away" />
                                <span class="text-sm font-medium">Away</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="lg" status="busy" />
                                <span class="text-sm font-medium">Busy</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-tyro-dashboard::gravatar email="me@hasin.me" size="lg" status="offline" />
                                <span class="text-sm font-medium">Offline</span>
                            </div>
                        </div>
                    </div>

                    <!-- Fallbacks & Initials -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-6 uppercase tracking-wider">Fallbacks & Initials</p>
                        <div class="space-y-6">
                            <div class="flex flex-wrap gap-4">
                                <x-tyro-dashboard::gravatar name="John Doe" color="primary" variant="solid" size="lg" />
                                <x-tyro-dashboard::gravatar name="Jane Smith" color="success" variant="soft" size="lg" />
                                <x-tyro-dashboard::gravatar name="Robert Brown" color="warning" variant="outline" size="lg" />
                                <x-tyro-dashboard::gravatar name="Alice Wilson" color="destructive" variant="solid" size="lg" />
                                <x-tyro-dashboard::gravatar name="Charlie Davis" color="purple" variant="soft" size="lg" />
                                <x-tyro-dashboard::gravatar name="Eva Green" color="pink" variant="outline" size="lg" />
                            </div>
                            <p class="text-sm text-muted-foreground italic">Initials are automatically generated when the Gravatar image is unavailable or no email is provided.</p>
                        </div>
                    </div>

                    <!-- Gravatar Integration -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-6 uppercase tracking-wider">Gravatar Default Types</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="nonexistent@example.com" size="lg" default="mp" />
                                <p class="text-xs text-muted-foreground">Mystery Person</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="nonexistent@example.com" size="lg" default="identicon" />
                                <p class="text-xs text-muted-foreground">Identicon</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="nonexistent@example.com" size="lg" default="monsterid" />
                                <p class="text-xs text-muted-foreground">MonsterID</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="nonexistent@example.com" size="lg" default="wavatar" />
                                <p class="text-xs text-muted-foreground">Wavatar</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="nonexistent@example.com" size="lg" default="retro" />
                                <p class="text-xs text-muted-foreground">Retro</p>
                            </div>
                            <div class="text-center space-y-2">
                                <x-tyro-dashboard::gravatar email="nonexistent@example.com" size="lg" default="robohash" />
                                <p class="text-xs text-muted-foreground">Robohash</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generic Avatars -->
            <div class="mb-12">
                <h3 class="text-xl font-bold border-b pb-2 mb-8">Generic Avatars</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    {{-- Initials --}}
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-6 uppercase tracking-wider">Initials & Colors</p>
                        <div class="flex flex-wrap gap-4">
                            <x-tyro-dashboard::avatar initials="JD" color="blue" variant="solid" size="lg" />
                            <x-tyro-dashboard::avatar initials="SM" color="green" variant="soft" size="lg" />
                            <x-tyro-dashboard::avatar initials="RB" color="orange" variant="outline" size="lg" />
                            <x-tyro-dashboard::avatar initials="AW" color="pink" variant="solid" size="lg" />
                        </div>
                    </div>

                    {{-- Image & Status --}}
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-6 uppercase tracking-wider">Images & Status</p>
                        <div class="flex flex-wrap gap-8">
                            <x-tyro-dashboard::avatar src="https://i.pravatar.cc/150?u=1" size="lg" status="online" />
                            <x-tyro-dashboard::avatar src="https://i.pravatar.cc/150?u=2" size="lg" status="away" rounded="rounded" />
                            <x-tyro-dashboard::avatar src="https://i.pravatar.cc/150?u=3" size="lg" status="busy" />
                            <x-tyro-dashboard::avatar src="https://i.pravatar.cc/150?u=4" size="lg" status="offline" rounded="rounded" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Search -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Image Search</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <x-tyro-dashboard::unsplash name="unsplash_image" label="Unsplash Search" apikey="YOUR KEY HERE" help="Type keywords to search Unsplash, then pick an image to fill the URL." />

                    <x-tyro-dashboard::pixabay name="pixabay_image" label="Pixabay Search" apikey="YOUR KEY HERE" help="Search Pixabay and select an image." />
                </div>
            </div>
        </section>

        <!-- Data Display Components -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Data & Containers</h2>

            <!-- Cards -->
            <div class="mb-12">
                <h3 class="text-xl font-bold border-b pb-2 mb-8">Premium Cards</h3>

                <div class="space-y-12">
                    <!-- Basic Variants -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Visual Style Variants</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <x-tyro-dashboard::card title="Default" subtitle="Elevation & Border">
                                <p class="text-xs text-muted-foreground">Standard depth with a subtle shadow and thin border.</p>
                            </x-tyro-dashboard::card>

                            <x-tyro-dashboard::card variant="outline" title="Outline" subtitle="Dashed High-Contrast">
                                <p class="text-xs text-muted-foreground">No shadow, transparent background, and a prominent dashed border.</p>
                            </x-tyro-dashboard::card>

                            <x-tyro-dashboard::card variant="flat" title="Flat" subtitle="Muted Background">
                                <p class="text-xs text-muted-foreground">No shadow or border. Uses the system muted background color.</p>
                            </x-tyro-dashboard::card>

                        </div>
                    </div>

                    <!-- Professional Colors -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Accent Colors & Gradients</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                            <x-tyro-dashboard::card color="primary" title="Primary Accent" hoverable>
                                <p class="text-xs text-muted-foreground">Top-border accent for branding.</p>
                            </x-tyro-dashboard::card>

                            <x-tyro-dashboard::card color="success" title="Success State" hoverable>
                                <p class="text-xs text-muted-foreground">Used for positive notifications.</p>
                            </x-tyro-dashboard::card>

                            <x-tyro-dashboard::card color="warning" title="Warning State" hoverable>
                                <p class="text-xs text-muted-foreground">Attention required for these items.</p>
                            </x-tyro-dashboard::card>

                            <x-tyro-dashboard::card color="purple" variant="gradient" title="Purple Gradient" hoverable>
                                <p class="text-xs text-muted-foreground">Subtle gradient background effect.</p>
                            </x-tyro-dashboard::card>

                            <x-tyro-dashboard::card color="primary" variant="solid" title="Solid Primary">
                                <p class="text-xs text-white/80">High visibility colored container.</p>
                            </x-tyro-dashboard::card>
                        </div>
                    </div>

                    <!-- Complex Layouts -->
                    <div>
                        <p class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-6">Complex Layouts</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Dashboard Card -->
                            <x-tyro-dashboard::card hoverable>
                                <x-slot name="title">Project Overview</x-slot>
                                <x-slot name="subtitle">Analytics for the last 30 days</x-slot>
                                <x-slot name="action">
                                    <x-tyro-dashboard::button variant="ghost" size="xs">View Detail</x-tyro-dashboard::button>
                                </x-slot>

                                <div class="space-y-4 pt-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm">Server Reliability</span>
                                        <span class="text-sm font-bold text-success">99.9%</span>
                                    </div>
                                    <x-tyro-dashboard::progress :value="99" color="success" size="xs" />

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm">Storage Usage</span>
                                        <span class="text-sm font-bold">128.5 GB / 250 GB</span>
                                    </div>
                                    <x-tyro-dashboard::progress :value="51" color="primary" size="xs" />
                                </div>

                                <x-slot name="footer">
                                    <div class="flex items-center justify-between text-xs text-muted-foreground">
                                        <span>Last synced: 2 minutes ago</span>
                                        <a href="#" class="text-primary hover:underline">Refresh Now</a>
                                    </div>
                                </x-slot>
                            </x-tyro-dashboard::card>

                            <!-- Media Card -->
                            <x-tyro-dashboard::card padding="none" hoverable>
                                <div class="aspect-video w-full overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80" class="w-full h-full object-cover" />
                                </div>
                                <div class="p-6">
                                    <h3 class="font-bold text-lg">Modern Web Design 2026</h3>
                                    <p class="text-sm text-muted-foreground mt-2">Explore the upcoming trends in UI/UX design and how to implement them in your next Laravel project.</p>
                                    <div class="mt-6 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <x-tyro-dashboard::gravatar email="me@hasin.me" size="24" rounded="full" />
                                            <span class="text-xs font-medium">Hasin Hayder</span>
                                        </div>
                                        <x-tyro-dashboard::badge variant="soft" color="blue">Tutorial</x-tyro-dashboard::badge>
                                    </div>
                                </div>
                            </x-tyro-dashboard::card>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Tables</h3>

                @php
                    // Common table data used across examples
                    use Illuminate\Support\Facades\Blade;
                    
                    $activeBadge = Blade::render('<x-tyro-dashboard::badge variant="solid" color="success">Active</x-tyro-dashboard::badge>');
                    $pendingBadge = Blade::render('<x-tyro-dashboard::badge variant="solid" color="warning">Pending</x-tyro-dashboard::badge>');
                    $inactiveBadge = Blade::render('<x-tyro-dashboard::badge variant="solid" color="destructive">Inactive</x-tyro-dashboard::badge>');
                    
                    $basicTableData = [
                        ['John Doe', 'john@example.com', 'Admin', $activeBadge],
                        ['Jane Smith', 'jane@example.com', 'User', $activeBadge],
                        ['Bob Johnson', 'bob@example.com', 'Moderator', $pendingBadge],
                        ['Alice Williams', 'alice@example.com', 'User', $inactiveBadge],
                    ];

                    $productTableData = [
                        ['MacBook Pro', 'Laptops', '$2,499', '12'],
                        ['iPhone 15', 'Phones', '$999', '45'],
                        ['AirPods Pro', 'Audio', '$249', '78'],
                        ['iPad Air', 'Tablets', '$599', '23'],
                        ['Apple Watch', 'Wearables', '$399', '34'],
                    ];

                    $taskTableData = [
                        ['#001', 'Update documentation', 'High', '2026-02-01'],
                        ['#002', 'Fix bug in login', 'Critical', '2026-01-31'],
                        ['#003', 'Design new feature', 'Medium', '2026-02-05'],
                    ];

                    $employeeTableData = [
                        ['John Doe', 'john@example.com', 'Engineering', '$85,000', '2023-01-15'],
                        ['Jane Smith', 'jane@example.com', 'Design', '$72,000', '2023-03-20'],
                        ['Bob Johnson', 'bob@example.com', 'Sales', '$65,000', '2023-06-10'],
                        ['Alice Williams', 'alice@example.com', 'Marketing', '$68,000', '2023-02-28'],
                        ['Charlie Brown', 'charlie@example.com', 'Engineering', '$92,000', '2022-11-05'],
                        ['Diana Prince', 'diana@example.com', 'HR', '$70,000', '2023-05-12'],
                        ['Eve Anderson', 'eve@example.com', 'Engineering', '$88,000', '2023-04-18'],
                        ['Frank Miller', 'frank@example.com', 'Sales', '$71,000', '2023-07-22'],
                    ];

                    $employeeStatusTableData = [
                        ['John Doe', 'john@example.com', 'Engineering', $activeBadge, '2023-01-15'],
                        ['Jane Smith', 'jane@example.com', 'Design', $activeBadge, '2023-03-20'],
                        ['Bob Johnson', 'bob@example.com', 'Sales', $pendingBadge, '2023-06-10'],
                        ['Alice Williams', 'alice@example.com', 'Marketing', $activeBadge, '2023-02-28'],
                        ['Charlie Brown', 'charlie@example.com', 'Engineering', $inactiveBadge, '2022-11-05'],
                        ['Diana Prince', 'diana@example.com', 'HR', $activeBadge, '2023-05-12'],
                        ['Eve Anderson', 'eve@example.com', 'Engineering', $activeBadge, '2023-04-18'],
                        ['Frank Miller', 'frank@example.com', 'Sales', $pendingBadge, '2023-07-22'],
                    ];

                    $selectableTableData = [
                        ['1', 'John Doe', 'john@example.com', 'Engineering', $activeBadge],
                        ['2', 'Jane Smith', 'jane@example.com', 'Design', $activeBadge],
                        ['3', 'Bob Johnson', 'bob@example.com', 'Sales', $pendingBadge],
                        ['4', 'Alice Williams', 'alice@example.com', 'Marketing', $activeBadge],
                        ['5', 'Charlie Brown', 'charlie@example.com', 'Engineering', $inactiveBadge],
                    ];

                    $fullFeaturedTableData = [
                        ['101', 'John Doe', 'john@example.com', 'Engineering', $activeBadge, '$85,000'],
                        ['102', 'Jane Smith', 'jane@example.com', 'Design', $activeBadge, '$72,000'],
                        ['103', 'Bob Johnson', 'bob@example.com', 'Sales', $pendingBadge, '$65,000'],
                        ['104', 'Alice Williams', 'alice@example.com', 'Marketing', $activeBadge, '$68,000'],
                        ['105', 'Charlie Brown', 'charlie@example.com', 'Engineering', $inactiveBadge, '$92,000'],
                        ['106', 'Diana Prince', 'diana@example.com', 'HR', $activeBadge, '$70,000'],
                        ['107', 'Eve Anderson', 'eve@example.com', 'Engineering', $activeBadge, '$88,000'],
                        ['108', 'Frank Miller', 'frank@example.com', 'Sales', $pendingBadge, '$71,000'],
                    ];
                @endphp

                <!-- Basic Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Basic Table</p>
                    <x-tyro-dashboard::table 
                        :headers="['Name', 'Email', 'Role', 'Status']" 
                        :data="$basicTableData" />
                </div>

                <!-- Striped Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Striped & Bordered Table</p>
                    <x-tyro-dashboard::table 
                        :headers="['Product', 'Category', 'Price', 'Stock']" 
                        :data="$productTableData" 
                        striped 
                        bordered />
                </div>

                <!-- Compact Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Compact Table</p>
                    <x-tyro-dashboard::table 
                        :headers="['ID', 'Task', 'Priority', 'Due Date']" 
                        :data="$taskTableData" 
                        compact 
                        hoverable />
                </div>

                <!-- Sortable Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Sortable Table (Click headers to sort)</p>
                    <x-tyro-dashboard::table 
                        :headers="['Name', 'Email', 'Department', 'Salary', 'Join Date']" 
                        :data="$employeeTableData" 
                        striped 
                        sortable />
                </div>

                <!-- Searchable Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Searchable Table (Search across all columns)</p>
                    <x-tyro-dashboard::table 
                        :headers="['Name', 'Email', 'Department', 'Salary', 'Join Date']" 
                        :data="$employeeTableData" 
                        searchable 
                        striped 
                        sortable />
                </div>

                <!-- Selective Searchable Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Selective Searchable Table (Only Name and Department are searchable)</p>
                    @php
                        $selectiveSearchHeaders = [
                            ['label' => 'Name', 'key' => 'name', 'searchable' => true],
                            ['label' => 'Email', 'key' => 'email', 'searchable' => false],
                            ['label' => 'Department', 'key' => 'department', 'searchable' => true],
                            ['label' => 'Salary', 'key' => 'salary', 'searchable' => false],
                            ['label' => 'Join Date', 'key' => 'join_date', 'searchable' => false],
                        ];
                    @endphp
                    <x-tyro-dashboard::table 
                        :headers="$selectiveSearchHeaders" 
                        :data="$employeeTableData" 
                        searchable 
                        striped 
                        sortable />
                </div>

                <!-- Filterable Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Filterable Table (Filter by column values)</p>
                    <x-tyro-dashboard::table 
                        :headers="['Name', 'Email', 'Department', 'Status', 'Join Date']" 
                        :data="$employeeStatusTableData" 
                        filterable 
                        striped 
                        sortable />
                </div>

                <!-- Selective Filterable Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Selective Filterable Table (Only Department and Status are filterable)</p>
                    @php
                        $selectiveHeaders = [
                            ['label' => 'Name', 'key' => 'name', 'filterable' => false],
                            ['label' => 'Email', 'key' => 'email', 'filterable' => false],
                            ['label' => 'Department', 'key' => 'department', 'filterable' => true],
                            ['label' => 'Status', 'key' => 'status', 'filterable' => true],
                            ['label' => 'Join Date', 'key' => 'join_date', 'filterable' => false],
                        ];
                    @endphp
                    <x-tyro-dashboard::table 
                        :headers="$selectiveHeaders" 
                        :data="$employeeStatusTableData" 
                        filterable 
                        striped 
                        sortable />
                </div>

                <!-- Searchable + Filterable Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Searchable + Filterable Table (Combined features)</p>
                    <x-tyro-dashboard::table 
                        :headers="['Name', 'Email', 'Department', 'Status', 'Join Date']" 
                        :data="$employeeStatusTableData" 
                        searchable 
                        filterable 
                        striped 
                        sortable />
                </div>

                <!-- Selectable Table with Form -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Selectable Table (Checkbox selection with bulk actions)</p>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Selected IDs: ' + this.querySelector('input[name=user_ids]').value);">
                        <x-tyro-dashboard::table 
                            :headers="['ID', 'Name', 'Email', 'Department', 'Status']" 
                            :data="$selectableTableData" 
                            name="user_ids"
                            selectable 
                            striped 
                            sortable />
                        <div class="mt-4 flex gap-2">
                            <x-tyro-dashboard::button type="submit">
                                Process Selected Users
                            </x-tyro-dashboard::button>
                            <x-tyro-dashboard::button type="button" variant="secondary" onclick="alert('This would export selected users')">
                                Export Selected
                            </x-tyro-dashboard::button>
                        </div>
                    </form>
                </div>

                <!-- Full Featured Table -->
                <div class="mb-6">
                    <p class="text-sm text-muted-foreground mb-3">Full Featured Table (All features enabled)</p>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Selected IDs: ' + this.querySelector('input[name=employee_ids]').value);">
                        <x-tyro-dashboard::table 
                            :headers="['ID', 'Name', 'Email', 'Department', 'Status', 'Salary']" 
                            :data="$fullFeaturedTableData" 
                            name="employee_ids"
                            searchable 
                            filterable 
                            selectable 
                            striped 
                            sortable />
                        <div class="mt-4 flex gap-2">
                            <x-tyro-dashboard::button type="submit">
                                Bulk Action on Selected
                            </x-tyro-dashboard::button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Tabs</h3>
                <x-tyro-dashboard::tabs defaultTab="profile">
                    <x-slot name="tabs">
                        <x-tyro-dashboard::tab name="profile" label="Profile" />
                        <x-tyro-dashboard::tab name="settings" label="Settings" />
                        <x-tyro-dashboard::tab name="notif_tab" label="Notifications" />
                        <x-tyro-dashboard::tab name="billing" label="Billing" icon='<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>' />
                    </x-slot>

                    <x-tyro-dashboard::tabpanel name="profile">
                        <div class="p-4 rounded-lg border border-input bg-muted/30">
                            <h4 class="font-semibold mb-2">Profile Information</h4>
                            <p class="text-sm text-muted-foreground">Manage your profile details and personal information.</p>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    <x-tyro-dashboard::gravatar email="user@example.com" :size="48" rounded="full" />
                                    <div>
                                        <p class="font-medium">John Doe</p>
                                        <p class="text-sm text-muted-foreground">john@example.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-tyro-dashboard::tabpanel>

                    <x-tyro-dashboard::tabpanel name="settings">
                        <div class="p-4 rounded-lg border border-input bg-muted/30">
                            <h4 class="font-semibold mb-2">Account Settings</h4>
                            <p class="text-sm text-muted-foreground">Configure your account preferences and security options.</p>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Two-factor authentication</span>
                                    <x-tyro-dashboard::badge variant="success">Enabled</x-tyro-dashboard::badge>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm">Email notifications</span>
                                    <x-tyro-dashboard::badge variant="secondary">On</x-tyro-dashboard::badge>
                                </div>
                            </div>
                        </div>
                    </x-tyro-dashboard::tabpanel>

                    <x-tyro-dashboard::tabpanel name="notif_tab">
                        <div class="p-4 rounded-lg border border-input bg-muted/30">
                            <h4 class="font-semibold mb-2">Notification Preferences</h4>
                            <p class="text-sm text-muted-foreground">Choose what notifications you want to receive.</p>
                            <div class="mt-4 space-y-3">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="rounded">
                                    <span class="text-sm">Email notifications</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" checked class="rounded">
                                    <span class="text-sm">Push notifications</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" class="rounded">
                                    <span class="text-sm">SMS notifications</span>
                                </label>
                            </div>
                        </div>
                    </x-tyro-dashboard::tabpanel>

                    <x-tyro-dashboard::tabpanel name="billing">
                        <div class="p-4 rounded-lg border border-input bg-muted/30">
                            <h4 class="font-semibold mb-2">Billing Information</h4>
                            <p class="text-sm text-muted-foreground">Manage your subscription and payment methods.</p>
                            <div class="mt-4">
                                <x-tyro-dashboard::badge variant="info">Pro Plan - $29/month</x-tyro-dashboard::badge>
                            </div>
                        </div>
                    </x-tyro-dashboard::tabpanel>
                </x-tyro-dashboard::tabs>
            </div>
        </section>

        <!-- Modal Component -->
        <section>
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Modal Dialogs</h2>
            <p class="text-muted-foreground mb-6">Powerful, customizable modal component with event firing and excellent UX.</p>

            <div class="space-y-8">
                <!-- Basic Modal -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Basic Modal</h3>
                    <button 
                        @click="$dispatch('modal-open', { id: 'basic-modal' })"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Open Basic Modal
                    </button>

                    <x-tyro-dashboard::modal id="basic-modal" title="Welcome">
                        <p>This is a basic modal with a title and some content.</p>
                        <p class="mt-4">Click the X button, press ESC, or click outside to close.</p>
                        
                        <x-slot:footer>
                            <button 
                                @click="$dispatch('modal-close', { id: 'basic-modal' })"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                            >
                                Cancel
                            </button>
                            <button 
                                @click="$dispatch('modal-close', { id: 'basic-modal' })"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            >
                                Confirm
                            </button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>
                </div>

                <!-- Size Variations -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Size Variations</h3>
                    <div class="flex flex-wrap gap-2">
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-sm' })"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
                        >
                            Small
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-lg' })"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
                        >
                            Large
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-xl' })"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
                        >
                            Extra Large
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-full' })"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700"
                        >
                            Fullscreen
                        </button>
                    </div>

                    <x-tyro-dashboard::modal id="modal-sm" title="Small Modal" size="sm">
                        <p>This is a small modal (24rem max-width).</p>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal id="modal-lg" title="Large Modal" size="lg">
                        <p>This is a large modal (32rem max-width).</p>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal id="modal-xl" title="Extra Large Modal" size="xl">
                        <p>This is an extra large modal (36rem max-width).</p>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal id="modal-full" title="Fullscreen Modal" :fullscreen="true">
                        <p>This is a fullscreen modal that takes up the entire viewport.</p>
                    </x-tyro-dashboard::modal>
                </div>

                <!-- Custom Header -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Custom Header</h3>
                    <button 
                        @click="$dispatch('modal-open', { id: 'custom-header-modal' })"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        Open Custom Header Modal
                    </button>

                    <x-tyro-dashboard::modal id="custom-header-modal">
                        <x-slot:header>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold">Success!</h3>
                            </div>
                        </x-slot:header>

                        <p>Your changes have been saved successfully.</p>
                    </x-tyro-dashboard::modal>
                </div>

                <!-- Advanced Features -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Advanced Features</h3>
                    <div class="flex flex-wrap gap-2">
                        <button 
                            @click="$dispatch('modal-open', { id: 'no-backdrop-close' })"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        >
                            No Backdrop Close
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'blur-modal' })"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                        >
                            Backdrop Blur
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'scrollable-modal' })"
                            class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700"
                        >
                            Scrollable Content
                        </button>
                    </div>

                    <x-tyro-dashboard::modal 
                        id="no-backdrop-close" 
                        title="Important Notice"
                        :closeOnBackdrop="false"
                        :closeOnEscape="false"
                    >
                        <p>This modal cannot be closed by clicking outside or pressing ESC.</p>
                        <p class="mt-4">You must click the Close button or the X icon.</p>
                        
                        <x-slot:footer>
                            <button 
                                @click="$dispatch('modal-close', { id: 'no-backdrop-close' })"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                            >
                                I Understand
                            </button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal 
                        id="blur-modal" 
                        title="Blurred Background"
                        :backdropBlur="true"
                        size="lg"
                    >
                        <p>Notice the blurred backdrop effect behind this modal.</p>
                        <p class="mt-4">This creates a nice visual separation from the background content.</p>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal id="scrollable-modal" title="Terms and Conditions" size="2xl">
                        <div class="space-y-4">
                            <h4 class="font-semibold">1. Introduction</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                            
                            <h4 class="font-semibold">2. Terms of Use</h4>
                            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            
                            <h4 class="font-semibold">3. Privacy Policy</h4>
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            
                            <h4 class="font-semibold">4. User Responsibilities</h4>
                            <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            
                            <h4 class="font-semibold">5. Additional Terms</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                            
                            <h4 class="font-semibold">6. Limitations</h4>
                            <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                        
                        <x-slot:footer>
                            <button 
                                @click="$dispatch('modal-close', { id: 'scrollable-modal' })"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                            >
                                Decline
                            </button>
                            <button 
                                @click="$dispatch('modal-close', { id: 'scrollable-modal' })"
                                class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700"
                            >
                                Accept Terms
                            </button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>
                </div>

                <!-- Premium Redesign Showcase -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Premium Redesign & Drawers</h3>
                    <div class="flex flex-wrap gap-2">
                        <button 
                            @click="$dispatch('modal-open', { id: 'modern-modal' })"
                            class="px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-900"
                        >
                            <i class="fa-solid fa-wand-magic-sparkles mr-2"></i> Modern Modal
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'drawer-right' })"
                            class="px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-900"
                        >
                            <i class="fa-solid fa-arrow-right-long mr-2"></i> Right Drawer
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'drawer-left' })"
                            class="px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-900"
                        >
                            <i class="fa-solid fa-arrow-left-long mr-2"></i> Left Drawer
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-logout' })"
                            class="px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-900"
                        >
                            <i class="fa-solid fa-right-from-bracket mr-2"></i> Logout Modal
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-invite' })"
                            class="px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-900"
                        >
                            <i class="fa-solid fa-user-plus mr-2"></i> Invite Modal
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-success-premium' })"
                            class="px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-900"
                        >
                            <i class="fa-solid fa-square-check mr-2"></i> Success Modal
                        </button>
                    </div>

                    <!-- Modern Modal with Icon & Subtitle -->
                    <x-tyro-dashboard::modal 
                        id="modern-modal" 
                        title="Premium Feature" 
                        subtitle="This is part of the redesigned modal component experience."
                        icon="fa-solid fa-gem"
                        size="md"
                    >
                        <div class="text-center py-6">
                            <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-shield-halved text-4xl text-blue-600"></i>
                            </div>
                            <h4 class="text-xl font-bold mb-2">Security Enhanced</h4>
                            <p class="text-muted-foreground">The new modal system supports advanced transitions and glassmorphism backdrops out of the box.</p>
                        </div>
                        
                        <x-slot:footer>
                            <button 
                                @click="$dispatch('modal-close', { id: 'modern-modal' })"
                                class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors"
                            >
                                Get Started
                            </button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <!-- Logout Confirmation Modal -->
                    <x-tyro-dashboard::modal 
                        id="modal-logout" 
                        title="Sign Out" 
                        subtitle="Are you sure you want to exit?"
                        icon="fa-solid fa-power-off"
                        size="sm"
                    >
                        <div class="text-center py-4">
                            <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                            </div>
                            <p class="text-muted-foreground">You will need to sign back in to access your dashboard settings and projects.</p>
                        </div>
                        
                        <x-slot:footer>
                            <div class="flex gap-2 w-full">
                                <button 
                                    @click="$dispatch('modal-close', { id: 'modal-logout' })"
                                    class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 font-medium"
                                >
                                    Stay Logged In
                                </button>
                                <button 
                                    @click="$dispatch('modal-close', { id: 'modal-logout' })"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
                                >
                                    Log Out
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <!-- Invite Member Modal -->
                    <x-tyro-dashboard::modal 
                        id="modal-invite" 
                        title="Invite Team Member" 
                        subtitle="Collaborate on your current workspace"
                        icon="fa-solid fa-users-viewfinder"
                        size="md"
                    >
                        <div class="space-y-4 py-2">
                            <div class="flex items-center gap-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                                <div class="w-10 h-10 bg-white dark:bg-gray-900 rounded-lg flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-blue-800 dark:text-blue-300 uppercase tracking-wider">Pro Feature</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400">Invited members get immediate access.</p>
                                </div>
                            </div>
                            
                            <x-tyro-dashboard::text name="invite_email" label="Email Address" placeholder="colleague@company.com" />
                            
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Workspace Role</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button class="flex flex-col items-start p-3 border-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20 rounded-xl text-left">
                                        <span class="text-sm font-bold text-blue-700 dark:text-blue-300">Editor</span>
                                        <span class="text-xs text-blue-600 dark:text-blue-400">Can edit projects</span>
                                    </button>
                                    <button class="flex flex-col items-start p-3 border rounded-xl text-left hover:border-gray-300">
                                        <span class="text-sm font-bold">Viewer</span>
                                        <span class="text-xs text-muted-foreground">Read-only access</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <x-slot:footer>
                            <button 
                                @click="$dispatch('modal-close', { id: 'modal-invite' })"
                                class="px-6 py-2 bg-slate-900 dark:bg-slate-100 dark:text-gray-900 text-white rounded-xl font-bold hover:opacity-90 transition-opacity"
                            >
                                Send Invitation
                            </button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <!-- Premium Success Modal -->
                    <x-tyro-dashboard::modal 
                        id="modal-success-premium" 
                        title="Payment Completed" 
                        subtitle="Your transaction was processed successfully"
                        icon="fa-solid fa-receipt"
                        size="md"
                    >
                        <div class="text-center py-6 px-4">
                            <div class="relative w-24 h-24 mx-auto mb-6">
                                <div class="absolute inset-0 bg-green-100 dark:bg-green-900/30 rounded-full animate-ping opacity-20"></div>
                                <div class="relative w-24 h-24 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center text-green-600">
                                    <i class="fa-solid fa-check text-4xl"></i>
                                </div>
                            </div>
                            <h4 class="text-2xl font-black mb-2 tracking-tight">Success!</h4>
                            <p class="text-muted-foreground mb-6">Your order #77129 has been confirmed. We've sent a receipt to your email address.</p>
                            
                            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 text-left">
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm text-muted-foreground">Amount Paid</span>
                                    <span class="text-sm font-bold">$129.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-muted-foreground">Payment Method</span>
                                    <span class="text-sm font-bold">•••• 4242</span>
                                </div>
                            </div>
                        </div>
                        
                        <x-slot:footer>
                            <button 
                                @click="$dispatch('modal-close', { id: 'modal-success-premium' })"
                                class="w-full py-3 bg-green-600 text-white rounded-2xl font-bold hover:bg-green-700 transition-all shadow-lg shadow-green-500/20 active:scale-[0.98]"
                            >
                                Done
                            </button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <!-- Right Drawer -->
                    <x-tyro-dashboard::modal 
                        id="drawer-right" 
                        variant="drawer-right"
                        title="User Settings" 
                        subtitle="Manage your profile and preferences"
                        icon="fa-solid fa-user-gear"
                        size="md"
                    >
                        <div class="space-y-6">
                            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-center text-muted-foreground">Drawer variants are perfect for complex forms or secondary navigation.</p>
                            </div>
                            
                            <x-tyro-dashboard::text name="drawer_name" label="Full Name" placeholder="John Doe" />
                            <x-tyro-dashboard::email name="drawer_email" label="Email Address" placeholder="john@example.com" />
                            
                            <div class="space-y-2">
                                <label class="text-sm font-medium">Notifications</label>
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-900 rounded-lg border">
                                    <span class="text-sm">Email Updates</span>
                                    <div class="w-10 h-6 bg-blue-600 rounded-full relative">
                                        <div class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <x-slot:footer>
                            <button 
                                @click="$dispatch('modal-close', { id: 'drawer-right' })"
                                class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button 
                                @click="$dispatch('modal-close', { id: 'drawer-right' })"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                            >
                                Save Changes
                            </button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <!-- Left Drawer -->
                    <x-tyro-dashboard::modal 
                        id="drawer-left" 
                        variant="drawer-left"
                        title="Navigation" 
                        subtitle="Quick access to dashboard sections"
                        icon="fa-solid fa-bars"
                        size="sm"
                    >
                        <nav class="space-y-1">
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg font-medium">
                                <i class="fa-solid fa-house"></i> Dashboard
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-muted-foreground hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fa-solid fa-chart-line"></i> Analytics
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-muted-foreground hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fa-solid fa-users"></i> Customers
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-muted-foreground hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fa-solid fa-file-invoice-dollar"></i> Sales
                            </a>
                            <div class="py-4 px-3">
                                <div class="h-px bg-gray-200 dark:bg-gray-700"></div>
                            </div>
                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-muted-foreground hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <i class="fa-solid fa-gear"></i> Settings
                            </a>
                        </nav>
                    </x-tyro-dashboard::modal>
                </div>

                <!-- Header Themes Showcase -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Header Color Themes</h3>
                    <div class="flex flex-wrap gap-2">
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-primary' })"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        >
                            Primary
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-success' })"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                        >
                            Success
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-danger' })"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        >
                            Danger
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-warning' })"
                            class="px-4 py-2 bg-amber-500 text-white rounded-md hover:bg-amber-600"
                        >
                            Warning
                        </button>
                        <button 
                            @click="$dispatch('modal-open', { id: 'modal-dark' })"
                            class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-black"
                        >
                            Dark
                        </button>
                    </div>

                    <x-tyro-dashboard::modal 
                        id="modal-primary" 
                        title="Upload Files" 
                        subtitle="Add documents to your project"
                        icon="fa-solid fa-cloud-arrow-up"
                        headerVariant="primary"
                    >
                        <p class="py-4">Choose files from your computer to start uploading.</p>
                        <x-slot:footer>
                            <button @click="$dispatch('modal-close', { id: 'modal-primary' })" class="px-4 py-2 border rounded-lg">Cancel</button>
                            <button @click="$dispatch('modal-close', { id: 'modal-primary' })" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Upload</button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal 
                        id="modal-success" 
                        title="Action Successful" 
                        subtitle="Your request has been processed"
                        icon="fa-solid fa-circle-check"
                        headerVariant="success"
                    >
                        <p class="py-4 text-center">Everything went according to plan! Your project is now up to date.</p>
                        <x-slot:footer>
                            <button @click="$dispatch('modal-close', { id: 'modal-success' })" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg">Great!</button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal 
                        id="modal-danger" 
                        title="Delete Content" 
                        subtitle="This action cannot be undone"
                        icon="fa-solid fa-triangle-exclamation"
                        headerVariant="danger"
                    >
                        <p class="py-4 font-medium text-red-600">Are you absolutely sure you want to delete this folder?</p>
                        <x-slot:footer>
                            <button @click="$dispatch('modal-close', { id: 'modal-danger' })" class="px-4 py-2 border rounded-lg">No, Keep it</button>
                            <button @click="$dispatch('modal-close', { id: 'modal-danger' })" class="px-4 py-2 bg-red-600 text-white rounded-lg">Yes, Delete</button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal 
                        id="modal-warning" 
                        title="Account Expiring" 
                        subtitle="Update your payment details soon"
                        icon="fa-solid fa-clock-rotate-left"
                        headerVariant="warning"
                    >
                        <p class="py-4">Your subscription will expire in 3 days. Renew now to avoid service interruption.</p>
                        <x-slot:footer>
                            <button @click="$dispatch('modal-close', { id: 'modal-warning' })" class="w-full px-4 py-2 bg-amber-500 text-white rounded-lg">Renew Subscription</button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>

                    <x-tyro-dashboard::modal 
                        id="modal-dark" 
                        title="Editor Settings" 
                        subtitle="Configure your workspace"
                        icon="fa-solid fa-code"
                        headerVariant="dark"
                    >
                        <div class="space-y-4 py-2">
                            <p class="text-sm text-muted-foreground">Dark headers provide a sleek, focused look for advanced configuration dialogs.</p>
                            <div class="h-20 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-dashed text-muted-foreground">
                                Editor Preview Area
                            </div>
                        </div>
                        <x-slot:footer>
                            <button @click="$dispatch('modal-close', { id: 'modal-dark' })" class="px-4 py-2 border rounded-lg">Save</button>
                        </x-slot:footer>
                    </x-tyro-dashboard::modal>
                </div>

                <!-- Event Handling Demo -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Event Handling</h3>
                    <div x-data="{ eventLog: [] }">
                        <button 
                            @click="$dispatch('modal-open', { id: 'event-modal' })"
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700"
                        >
                            Open Event Modal
                        </button>

                        <div 
                            class="mt-4 p-4 bg-gray-100 rounded-md"
                            @modal-opening.window="eventLog.unshift('modal-opening fired at ' + new Date().toLocaleTimeString())"
                            @modal-opened.window="eventLog.unshift('modal-opened fired at ' + new Date().toLocaleTimeString())"
                            @modal-closing.window="eventLog.unshift('modal-closing fired at ' + new Date().toLocaleTimeString())"
                            @modal-closed.window="eventLog.unshift('modal-closed fired at ' + new Date().toLocaleTimeString())"
                        >
                            <h4 class="font-semibold mb-2">Event Log:</h4>
                            <ul class="space-y-1 text-sm">
                                <template x-for="(event, index) in eventLog.slice(0, 10)" :key="index">
                                    <li x-text="event" class="text-gray-700"></li>
                                </template>
                                <li x-show="eventLog.length === 0" class="text-gray-500 italic">No events yet...</li>
                            </ul>
                        </div>
                    </div>

                    <x-tyro-dashboard::modal id="event-modal" title="Event Demo">
                        <p>Open and close this modal to see events being fired.</p>
                        <p class="mt-4">Events: modal-opening, modal-opened, modal-closing, modal-closed</p>
                    </x-tyro-dashboard::modal>
                </div>
            </div>
        </section>

        <!-- Footer Component -->
        <section>
            <div class="mb-8">
                <h2 class="text-3xl font-black tracking-tight mb-2">Footer Component</h2>
                <p class="text-muted-foreground">Simple footer with three slot options: full, center, left/right.</p>
            </div>

            <div class="space-y-8">
                <!-- Full Width Footer -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Full Width Slot</h3>
                    <x-tyro-dashboard::footer>
                        <x-slot:full>
                            <div class="text-center">
                                <p class="text-sm text-muted-foreground">© 2026 Tyro Dashboard. All rights reserved.</p>
                            </div>
                        </x-slot:full>
                    </x-tyro-dashboard::footer>
                </div>

                <!-- Center Only Footer -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Center Slot</h3>
                    <x-tyro-dashboard::footer>
                        <x-slot:center>
                            <p class="text-sm text-muted-foreground">© 2026 Tyro Dashboard. All rights reserved.</p>
                        </x-slot:center>
                    </x-tyro-dashboard::footer>
                </div>

                <!-- Left and Right Footer -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Left & Right Slots</h3>
                    <x-tyro-dashboard::footer>
                        <x-slot:left>
                            <p class="text-sm text-muted-foreground">© 2026 Tyro Dashboard</p>
                        </x-slot:left>
                        <x-slot:right>
                            <div class="flex gap-4">
                                <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Privacy</a>
                                <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Terms</a>
                                <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Contact</a>
                            </div>
                        </x-slot:right>
                    </x-tyro-dashboard::footer>
                </div>

                <!-- Left Only -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Left Slot Only</h3>
                    <x-tyro-dashboard::footer>
                        <x-slot:left>
                            <p class="text-sm text-muted-foreground">© 2026 Tyro Dashboard. All rights reserved.</p>
                        </x-slot:left>
                    </x-tyro-dashboard::footer>
                </div>

                <!-- Right Only -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Right Slot Only</h3>
                    <x-tyro-dashboard::footer>
                        <x-slot:right>
                            <div class="flex gap-4">
                                <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Privacy</a>
                                <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Terms</a>
                                <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Contact</a>
                            </div>
                        </x-slot:right>
                    </x-tyro-dashboard::footer>
                </div>

                <!-- Without Border -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Without Border</h3>
                    <x-tyro-dashboard::footer :border="false">
                        <x-slot:left>
                            <p class="text-sm text-muted-foreground">© 2026 Tyro Dashboard</p>
                        </x-slot:left>
                        <x-slot:right>
                            <p class="text-sm text-muted-foreground">Made with ❤️</p>
                        </x-slot:right>
                    </x-tyro-dashboard::footer>
                </div>

                <!-- Complex Example -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Complex Example (Left & Right)</h3>
                    <x-tyro-dashboard::footer>
                        <x-slot:left>
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg"></div>
                                <div>
                                    <p class="text-sm font-semibold">Tyro Dashboard</p>
                                    <p class="text-xs text-muted-foreground">© 2026 All rights reserved</p>
                                </div>
                            </div>
                        </x-slot:left>
                        <x-slot:right>
                            <div class="flex items-center gap-6">
                                <div class="flex gap-4">
                                    <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">About</a>
                                    <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Privacy</a>
                                    <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Terms</a>
                                </div>
                                <div class="flex gap-2">
                                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-muted hover:bg-primary hover:text-white transition-colors">
                                        <i class="fab fa-twitter text-sm"></i>
                                    </a>
                                    <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-muted hover:bg-primary hover:text-white transition-colors">
                                        <i class="fab fa-github text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </x-slot:right>
                    </x-tyro-dashboard::footer>
                </div>

                <!-- Full Width Footer -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Full Width Footer</h3>
                    <x-tyro-dashboard::footer :container="false">
                        <x-slot:left>
                            <p class="text-sm text-muted-foreground">© 2026 Tyro Dashboard</p>
                        </x-slot:left>
                        <x-slot:right>
                            <p class="text-sm text-muted-foreground">Full width - no container</p>
                        </x-slot:right>
                    </x-tyro-dashboard::footer>
                </div>
            </div>
        </section>

        <!-- Complete Form Example -->
        <section class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-black tracking-tight mb-2">Complete Form Showcase</h2>
                <p class="text-muted-foreground">A real-world example demonstrating how to combine different components into a cohesive, high-end interface.</p>
            </div>

            <x-tyro-dashboard::card class="overflow-hidden border-none shadow-2xl shadow-blue-500/10">
                <form class="divide-y divide-gray-100 dark:divide-gray-800">
                    <!-- Section 1: General Information -->
                    <div class="p-6 md:p-8">
                        <x-tyro-dashboard::heading 
                            title="General Information" 
                            subtitle="Basic details about your new project"
                            icon="fa-solid fa-circle-info"
                            iconColor="primary"
                            variant="solid"
                        />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-tyro-dashboard::text 
                                name="project_name" 
                                label="Project Name" 
                                placeholder="e.g. Tyro Dashboard Support"
                                required 
                            />
                            
                            <x-tyro-dashboard::icon-input 
                                name="project_url" 
                                label="Project URL" 
                                icon="fa-solid fa-link"
                                icon-position="left"
                                placeholder="tyro.dev/projects/..."
                            />

                            <x-tyro-dashboard::select 
                                name="project_priority" 
                                label="Priority" 
                                :options="['low' => 'Low Priority', 'medium' => 'Medium Priority', 'high' => 'High Priority']" 
                                required 
                            />

                            <x-tyro-dashboard::email 
                                name="owner_email" 
                                label="Owner Email" 
                                placeholder="owner@company.com"
                                required 
                            />
                        </div>
                    </div>

                    <!-- Section 2: Project Details & Categorization -->
                    <div class="p-6 md:p-8 bg-gray-50/50 dark:bg-gray-900/20">
                        <x-tyro-dashboard::heading 
                            title="Details & Categorization" 
                            subtitle="Define the scope and labels for this project"
                            icon="fa-solid fa-layer-group"
                            iconColor="indigo"
                            variant="solid"
                        />

                        <div class="space-y-6">
                            <x-tyro-dashboard::textarea 
                                name="project_description" 
                                label="Description" 
                                placeholder="Briefly describe the goals and objectives..."
                                rows="4" 
                            />

                            <x-tyro-dashboard::tags 
                                name="project_tags" 
                                label="Project Tags"
                                :value="['Laravel', 'UI/UX']"
                                placeholder="Add labels (press Enter)..."
                                color="info"
                            />
                        </div>
                    </div>

                    <!-- Section 3: Advanced Settings -->
                    <div class="p-6 md:p-8">
                        <x-tyro-dashboard::heading 
                            title="Advanced Configuration" 
                            subtitle="Team assignment and scheduling"
                            icon="fa-solid fa-sliders"
                            iconColor="purple"
                            variant="solid"
                        />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-tyro-dashboard::multiselect 
                                name="team_members" 
                                label="Assign Team" 
                                :options="[
                                    '1' => 'John Doe',
                                    '2' => 'Sarah Smith',
                                    '3' => 'Mike Johnson',
                                    '4' => 'Alex Wong'
                                ]"
                                placeholder="Select members..."
                            />

                            <x-tyro-dashboard::datepicker 
                                name="project_deadline" 
                                label="Deadline" 
                                placeholder="Select target date"
                            />
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-muted-foreground mb-4">Privacy & Notifications</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-900 rounded-2xl border">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-eye"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold">Public Visibility</p>
                                            <p class="text-xs text-muted-foreground">Allow others to view this project</p>
                                        </div>
                                    </div>
                                    <x-tyro-dashboard::toggle name="is_public" />
                                </div>

                                <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-900 rounded-2xl border">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 text-amber-600 rounded-lg flex items-center justify-center">
                                            <i class="fa-solid fa-bell"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold">Slack Alerts</p>
                                            <p class="text-xs text-muted-foreground">Sync updates to #notifications</p>
                                        </div>
                                    </div>
                                    <x-tyro-dashboard::toggle name="slack_sync" color="amber" checked />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="p-6 md:p-8 bg-gray-50 dark:bg-gray-900/40 flex flex-col md:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-muted-foreground italic">Last autosaved at {{ now()->format('H:i') }}</p>
                        <div class="flex gap-3 w-full md:w-auto">
                            <x-tyro-dashboard::button type="button" variant="outline" class="flex-1 md:px-8">
                                Save Draft
                            </x-tyro-dashboard::button>
                            <x-tyro-dashboard::button type="submit" class="flex-1 md:px-8 shadow-lg shadow-blue-500/20">
                                Create Project
                            </x-tyro-dashboard::button>
                        </div>
                    </div>
                </form>
            </x-tyro-dashboard::card>
        </section>

        <!-- Heading Component Examples -->
        <section class="space-y-8">
            <x-tyro-dashboard::heading 
                title="Heading Component" 
                subtitle="Versatile section titles with icons and actions"
                icon="fa-solid fa-heading"
                iconColor="dark"
                variant="subtle"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sizes -->
                <x-tyro-dashboard::card class="p-6 space-y-6">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-muted-foreground mb-4">Different Sizes</h4>
                    
                    <x-tyro-dashboard::heading 
                        size="sm"
                        title="Small Heading" 
                        subtitle="Perfect for compact sections"
                        icon="fa-solid fa-microchip"
                        iconColor="primary"
                    />

                    <x-tyro-dashboard::heading 
                        size="md"
                        title="Medium Heading" 
                        subtitle="The standard size for most layouts"
                        icon="fa-solid fa-cube"
                        iconColor="indigo"
                    />

                    <x-tyro-dashboard::heading 
                        size="lg"
                        title="Large Heading" 
                        subtitle="Great for main page sections"
                        icon="fa-solid fa-rocket"
                        iconColor="purple"
                        variant="solid"
                    />
                </x-tyro-dashboard::card>

                <!-- Variants -->
                <x-tyro-dashboard::card class="p-6 space-y-6">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-muted-foreground mb-4">Style Variants</h4>
                    
                    <x-tyro-dashboard::heading 
                        variant="solid"
                        title="Solid Variant" 
                        subtitle="Bold and high-contrast"
                        icon="fa-solid fa-shield-halved"
                        iconColor="success"
                    />

                    <x-tyro-dashboard::heading 
                        variant="subtle"
                        title="Subtle Variant" 
                        subtitle="Clean and modern (default)"
                        icon="fa-solid fa-leaf"
                        iconColor="success"
                    />

                    <x-tyro-dashboard::heading 
                        variant="ghost"
                        title="Ghost Variant" 
                        subtitle="Minimalist with no background"
                        icon="fa-solid fa-ghost"
                        iconColor="success"
                    />
                </x-tyro-dashboard::card>

                <!-- Alignment & Actions -->
                <x-tyro-dashboard::card class="md:col-span-2 p-6 space-y-8">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-muted-foreground mb-4">Alignment & Actions</h4>
                    
                    <x-tyro-dashboard::heading 
                        title="Project Settings" 
                        subtitle="Manage your project preferences and team members"
                        icon="fa-solid fa-gears"
                        iconColor="dark"
                    >
                        <x-slot:action>
                            <x-tyro-dashboard::button size="sm" variant="outline">Secondary</x-tyro-dashboard::button>
                            <x-tyro-dashboard::button size="sm">Primary Action</x-tyro-dashboard::button>
                        </x-slot:action>
                    </x-tyro-dashboard::heading>

                    <div class="h-px bg-gray-100 dark:bg-gray-800"></div>

                    <x-tyro-dashboard::heading 
                        align="center"
                        title="Ready to Scale?" 
                        subtitle="Join thousands of developers building amazing things with Tyro Dashboard."
                        icon="fa-solid fa-chart-line"
                        iconColor="indigo"
                        variant="solid"
                        size="lg"
                    />
                </x-tyro-dashboard::card>
            </div>
        <!-- Layout & Sections -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Layout & Sections</h2>
            <div class="space-y-12">
                <!-- 1. Fixed Width Section -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">1. Fixed Width (800px) & Centered</h3>
                    <x-tyro-dashboard::section 
                        title="Search Settings" 
                        description="Configure how search results are displayed and ranked." 
                        width="800px" 
                        align="center" 
                        variant="outline"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-tyro-dashboard::toggle name="enable_fuzzy" label="Enable Fuzzy Search" checked />
                            <x-tyro-dashboard::toggle name="show_snippets" label="Show Result Snippets" checked />
                            <x-tyro-dashboard::text name="results_per_page" label="Results Per Page" value="20" />
                            <x-tyro-dashboard::select name="sort_by" label="Default Sort" :options="['relevance' => 'Relevance', 'date' => 'Newest First', 'views' => 'Most Viewed']" value="relevance" />
                        </div>
                    </x-tyro-dashboard::section>
                </div>

                <!-- 2. Percentage Width Section -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">2. Percentage Width (60%)</h3>
                    <x-tyro-dashboard::section 
                        width="60%" 
                        variant="flat" 
                        title="Danger Zone" 
                        description="Actions here can be destructive and cannot be undone."
                    >
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg border border-red-100">
                            <div>
                                <h4 class="font-medium text-destructive">Delete Account</h4>
                                <p class="text-sm text-muted-foreground">Permanently remove all your data.</p>
                            </div>
                            <x-tyro-dashboard::button color="destructive" size="sm">Purge Data</x-tyro-dashboard::button>
                        </div>
                    </x-tyro-dashboard::section>
                </div>

                <!-- 3. Sidebar Layout (Fixed Sidebar + Flexible Main) -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">3. Sidebar Layout (Fixed 280px Sidebar + Flexible Main)</h3>
                    <x-tyro-dashboard::section layout="flex" gap="8" width="full" nowrap>
                        {{-- Sidebar --}}
                        <x-tyro-dashboard::section width="280px" flex="none" variant="flat" title="User Menu">
                            <x-tyro-dashboard::menu>
                                <x-tyro-dashboard::menu-item label="Profile" icon="fa-solid fa-user" />
                                <x-tyro-dashboard::menu-item label="Subscriptions" icon="fa-solid fa-credit-card" />
                                <x-tyro-dashboard::menu-item label="Security" icon="fa-solid fa-shield-halved" />
                                <x-tyro-dashboard::menu-item label="Notifications" icon="fa-solid fa-bell" />
                            </x-tyro-dashboard::menu>
                        </x-tyro-dashboard::section>

                        {{-- Main Content --}}
                        <x-tyro-dashboard::section flex="1" variant="card" title="Personal Details">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-tyro-dashboard::text name="first_name_ref" label="First Name" value="John" />
                                <x-tyro-dashboard::text name="last_name_ref" label="Last Name" value="Doe" />
                                <x-tyro-dashboard::email name="email_profile_ref" label="Email" value="john@example.com" />
                                <x-tyro-dashboard::phonenumber name="phone_profile_ref" label="Phone" value="+123456789" />
                            </div>
                            <div class="mt-6 flex justify-end">
                                <x-tyro-dashboard::button>Save Changes</x-tyro-dashboard::button>
                            </div>
                        </x-tyro-dashboard::section>
                    </x-tyro-dashboard::section>
                </div>

                <!-- 4. Kanban Board (4 Equal Columns) -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">4. Kanban Board (4 Equal Columns)</h3>
                    <x-tyro-dashboard::section layout="flex" gap="4" width="full" nowrap>
                        @php
                            $columns = [
                                ['title' => 'Backlog', 'count' => 5, 'color' => 'gray'],
                                ['title' => 'In Progress', 'count' => 3, 'color' => 'blue'],
                                ['title' => 'Review', 'count' => 2, 'color' => 'orange'],
                                ['title' => 'Completed', 'count' => 8, 'color' => 'green'],
                            ];
                        @endphp

                        @foreach($columns as $col)
                            <x-tyro-dashboard::section flex="1" variant="flat" class="min-h-[400px]">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-bold text-sm uppercase tracking-wider flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-{{ $col['color'] }}-500"></span>
                                        {{ $col['title'] }}
                                    </h4>
                                    <x-tyro-dashboard::badge size="sm" variant="outline">{{ $col['count'] }}</x-tyro-dashboard::badge>
                                </div>
                                
                                <div class="space-y-3">
                                    @for($i = 1; $i <= 2; $i++)
                                        <x-tyro-dashboard::card class="p-3 shadow-sm hover:shadow-md transition-shadow cursor-pointer border-none">
                                            <div class="text-xs font-semibold text-muted-foreground mb-1">TASK-10{{ $i }}</div>
                                            <div class="text-sm font-medium mb-3">Implement layout components for dashboard</div>
                                            <div class="flex items-center justify-between">
                                                <x-tyro-dashboard::badge size="sm" color="info">Feature</x-tyro-dashboard::badge>
                                                <x-tyro-dashboard::gravatar email="user@example.com" size="20" />
                                            </div>
                                        </x-tyro-dashboard::card>
                                    @endfor
                                </div>
                            </x-tyro-dashboard::section>
                        @endforeach
                    </x-tyro-dashboard::section>
                </div>
            </div>
        </section>
        <!-- Lists & Data -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Lists & Data</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- 1. Simple Divided List -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">Simple Divided List</h3>
                    <x-tyro-dashboard::section variant="card" class="p-0">
                        <x-tyro-dashboard::list>
                            <x-tyro-dashboard::list-item title="Notifications" subtitle="You have 3 unread alerts" icon="fa-solid fa-bell" />
                            <x-tyro-dashboard::list-item title="Private Messages" subtitle="No new messages" icon="fa-solid fa-envelope" />
                            <x-tyro-dashboard::list-item title="Storage" subtitle="75% of your quota used" icon="fa-solid fa-database">
                                <div class="mt-2">
                                    <x-tyro-dashboard::progress value="75" color="warning" size="sm" />
                                </div>
                            </x-tyro-dashboard::list-item>
                        </x-tyro-dashboard::list>
                    </x-tyro-dashboard::section>
                </div>

                <!-- 2. Bordered Interactive List -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">Bordered Interactive (Links)</h3>
                    <x-tyro-dashboard::list variant="bordered">
                        <x-tyro-dashboard::list-item href="#" title="Alex Rivera" subtitle="Product Designer" avatar="https://i.pravatar.cc/150?u=a" />
                        <x-tyro-dashboard::list-item href="#" title="Sarah Johnson" subtitle="Lead Developer" avatar="https://i.pravatar.cc/150?u=b" active />
                        <x-tyro-dashboard::list-item href="#" title="Michael Chen" subtitle="DevOps Engineer" avatar="https://i.pravatar.cc/150?u=c" />
                        <x-tyro-dashboard::list-item title="System Account" subtitle="Internal Use" icon="fa-solid fa-robot" disabled />
                    </x-tyro-dashboard::list>
                </div>

                <!-- 3. Zebra Style with Actions -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">Zebra Style with Actions</h3>
                    <x-tyro-dashboard::section variant="outline" class="p-0 overflow-hidden">
                        <x-tyro-dashboard::list variant="zebra">
                            <x-tyro-dashboard::list-item title="Invoice #8892" subtitle="Due in 5 days">
                                <x-slot:action>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold">$1,250.00</span>
                                        <x-tyro-dashboard::badge color="info">Sent</x-tyro-dashboard::badge>
                                    </div>
                                </x-slot:action>
                            </x-tyro-dashboard::list-item>
                            <x-tyro-dashboard::list-item title="Invoice #8891" subtitle="Paid on Oct 12">
                                <x-slot:action>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold">$4,800.00</span>
                                        <x-tyro-dashboard::badge color="success">Paid</x-tyro-dashboard::badge>
                                    </div>
                                </x-slot:action>
                            </x-tyro-dashboard::list-item>
                            <x-tyro-dashboard::list-item title="Invoice #8890" subtitle="Overdue by 2 days">
                                <x-slot:action>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-bold">$920.00</span>
                                        <x-tyro-dashboard::badge color="destructive">Late</x-tyro-dashboard::badge>
                                    </div>
                                </x-slot:action>
                            </x-tyro-dashboard::list-item>
                        </x-tyro-dashboard::list>
                    </x-tyro-dashboard::section>
                </div>

                <!-- 4. Separated Cards -->
                <div>
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">Separated Layout (Cards)</h3>
                    <x-tyro-dashboard::list variant="separated" gap="4">
                        <x-tyro-dashboard::list-item variant="card" href="#" title="Quarterly Security Audit" subtitle="Scheduled for Nov 1st" icon="fa-solid fa-shield-check" />
                        <x-tyro-dashboard::list-item variant="card" href="#" title="Database Maintenance" subtitle="Running tonight at 2 AM" icon="fa-solid fa-server" />
                        <x-tyro-dashboard::list-item variant="card" href="#" title="Monthly Backup" subtitle="Completed successfully" icon="fa-solid fa-cloud-arrow-up" />
                    </x-tyro-dashboard::list>
                </div>

                <!-- 5. Advanced Slot Content -->
                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-muted-foreground uppercase tracking-wider mb-4">Advanced Slot Content (Custom Layouts)</h3>
                    <x-tyro-dashboard::list variant="bordered">
                        <x-tyro-dashboard::list-item icon="fa-solid fa-cloud">
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold">Cloud Storage Sync</span>
                                    <x-tyro-dashboard::badge size="sm" variant="soft" color="primary">In Progress</x-tyro-dashboard::badge>
                                </div>
                                <x-tyro-dashboard::progress value="65" size="xs" color="primary" />
                                <div class="flex items-center justify-between text-[10px] text-muted-foreground uppercase tracking-widest font-bold">
                                    <div class="flex items-center gap-4">
                                        <span><i class="fa-solid fa-hdd mr-1"></i> 3.2 GB / 5 GB</span>
                                        <span><i class="fa-solid fa-clock mr-1"></i> 12 mins left</span>
                                    </div>
                                    <span>65% Complete</span>
                                </div>
                            </div>
                            <x-slot:action>
                                <x-tyro-dashboard::button variant="ghost" size="sm" icon="fa-solid fa-pause" />
                            </x-slot:action>
                        </x-tyro-dashboard::list-item>

                        <x-tyro-dashboard::list-item icon="fa-solid fa-microchip">
                            <div class="flex items-center justify-between w-full">
                                <div>
                                    <div class="text-sm font-semibold">CPU Usage</div>
                                    <div class="text-xs text-muted-foreground">Main processing unit</div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <div class="text-lg font-bold">24%</div>
                                        <div class="text-[10px] text-success font-bold uppercase tracking-tighter">Healthy</div>
                                    </div>
                                    <div class="w-32">
                                        <x-tyro-dashboard::progress value="24" size="xs" color="success" />
                                    </div>
                                </div>
                            </div>
                        </x-tyro-dashboard::list-item>
                    </x-tyro-dashboard::list>
                </div>
            </div>
        </section>
        <!-- Sidebar Components -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 pb-2 border-b">Sidebar Navigation</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- 1. Default Left Sidebar -->
                <div>
                    <h3 class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-4">1. Standard Left Sidebar</h3>
                    <div class="bg-muted p-8 rounded-xl border flex justify-center h-[700px]">
                        <x-tyro-dashboard::sidebar>
                            <x-slot:header>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white font-bold shadow-sm">T</div>
                                    <span class="font-bold">Tyro Admin</span>
                                </div>
                            </x-slot:header>

                            <x-tyro-dashboard::sidebar-group heading="Main">
                                <x-tyro-dashboard::sidebar-item label="Dashboard" icon="fa-solid fa-house" active />
                                <x-tyro-dashboard::sidebar-item label="Analytics" icon="fa-solid fa-chart-line" badge="Live" badgeColor="destructive" />
                                <x-tyro-dashboard::sidebar-item label="Reports" icon="fa-solid fa-file-lines" />
                            </x-tyro-dashboard::sidebar-group>

                            <x-tyro-dashboard::sidebar-group heading="Resources" help="System assets">
                                <x-tyro-dashboard::sidebar-item label="Media Library" icon="fa-solid fa-images" />
                                <x-tyro-dashboard::sidebar-item label="Documents" icon="fa-solid fa-folder-open" />
                            </x-tyro-dashboard::sidebar-group>

                            <x-slot:footer>
                                <x-tyro-dashboard::sidebar-item label="Settings" icon="fa-solid fa-gear" />
                                <x-tyro-dashboard::sidebar-item label="Support" icon="fa-solid fa-circle-question" />
                            </x-slot:footer>
                        </x-tyro-dashboard::sidebar>
                    </div>
                </div>

                <!-- 2. Collapsible & Right Aligned Card -->
                <div>
                    <h3 class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-4">2. Collapsible & Right Aligned (Card Style)</h3>
                    <div class="bg-muted p-8 rounded-xl border flex justify-center h-[700px]">
                        <x-tyro-dashboard::sidebar align="right" variant="card">
                            <x-slot:header>
                                <div class="px-2">
                                    <div class="text-sm font-bold mb-1">Hasin Hayder</div>
                                    <x-tyro-dashboard::star-rating rating="4.5" size="xs" />
                                    <div class="text-[10px] text-muted-foreground mt-1 font-bold uppercase tracking-wider">Pro Account</div>
                                </div>
                            </x-slot:header>

                            <x-tyro-dashboard::sidebar-group heading="Organization" collapsible>
                                <x-tyro-dashboard::sidebar-item label="Teams" icon="fa-solid fa-users-gear" />
                                <x-tyro-dashboard::sidebar-item label="Projects" icon="fa-solid fa-diagram-project" badge="12" />
                            </x-tyro-dashboard::sidebar-group>

                            <x-tyro-dashboard::sidebar-group heading="Account" collapsible collapsed="true" help="Manage your billing">
                                <x-tyro-dashboard::sidebar-item label="Billing" icon="fa-solid fa-credit-card" />
                                <x-tyro-dashboard::sidebar-item label="Usage" icon="fa-solid fa-gauge-high" />
                            </x-tyro-dashboard::sidebar-group>

                            <x-tyro-dashboard::sidebar-group heading="Settings" collapsible collapsed="true">
                                <x-tyro-dashboard::sidebar-item label="Security" icon="fa-solid fa-shield-halved" />
                                <x-tyro-dashboard::sidebar-item label="API Keys" icon="fa-solid fa-key" />
                                <x-tyro-dashboard::sidebar-item label="Webhooks" icon="fa-solid fa-link" />
                            </x-tyro-dashboard::sidebar-group>

                            <x-slot:footer>
                                <div class="mt-4 p-4">
                                    <div class="bg-secondary/10 p-4 rounded-xl border border-secondary/20 mb-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-tyro-dashboard::avatar initials="AH" size="sm" />
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold">Alex Hunter</span>
                                                <span class="text-[10px] text-muted-foreground">Admin</span>
                                            </div>
                                        </div>
                                    </div>
                                    <x-tyro-dashboard::button variant="ghost" size="xs" color="destructive" icon="fa-solid fa-power-off" class="w-full justify-start px-3">
                                        Sign Out
                                    </x-tyro-dashboard::button>
                                </div>
                            </x-slot:footer>
                        </x-tyro-dashboard::sidebar>
                    </div>
                </div>

                <!-- 3. Custom Colored Sidebar -->
                <div class="lg:col-span-2">
                    <h3 class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-4">3. Custom Background & Font Colors</h3>
                    <div class="bg-muted p-8 rounded-xl border flex justify-center h-[700px]">
                        <x-tyro-dashboard::sidebar bg="#1e293b" color="#f8fafc" class="rounded-xl overflow-hidden border-none shadow-2xl">
                            <x-slot:header>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold">D</div>
                                    <span class="font-bold">Dark Theme</span>
                                </div>
                            </x-slot:header>

                            <x-tyro-dashboard::sidebar-group heading="Deep Insights">
                                <x-tyro-dashboard::sidebar-item label="Cloud Metrics" icon="fa-solid fa-cloud" active />
                                <x-tyro-dashboard::sidebar-item label="Network" icon="fa-solid fa-network-wired" badge="Up" badgeColor="success" />
                            </x-tyro-dashboard::sidebar-group>

                            <x-tyro-dashboard::sidebar-group heading="Global Settings" collapsible>
                                <x-tyro-dashboard::sidebar-item label="Endpoints" icon="fa-solid fa-server" />
                                <x-tyro-dashboard::sidebar-item label="SSL Certificates" icon="fa-solid fa-lock" />
                            </x-tyro-dashboard::sidebar-group>

                            <x-slot:footer>
                                <div class="px-4 pb-4">
                                    <x-tyro-dashboard::button bg="#334155" color="white" size="sm" class="w-full border-none hover:bg-slate-600">
                                        System Status: Optimal
                                    </x-tyro-dashboard::button>
                                </div>
                            </x-slot:footer>
                        </x-tyro-dashboard::sidebar>
                    </div>
                </div>

                <!-- Colorful Brand Sidebars -->
                <div class="lg:col-span-2">
                    <h3 class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-4 mt-8">4. Colorful Brand Sidebars</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {{-- Purple Sidebar --}}
                        <div class="bg-muted p-4 rounded-xl border flex justify-center h-[500px]">
                            <x-tyro-dashboard::sidebar bg="#7c3aed" color="white" class="rounded-lg shadow-lg">
                                <x-slot:header>
                                    <div class="flex items-center gap-2 px-2">
                                        <i class="fa-solid fa-bolt text-yellow-400"></i>
                                        <span class="font-bold">Lightning</span>
                                    </div>
                                </x-slot:header>
                                <x-tyro-dashboard::sidebar-group heading="Performance">
                                    <x-tyro-dashboard::sidebar-item label="Dashboard" icon="fa-solid fa-gauge" active />
                                    <x-tyro-dashboard::sidebar-item label="Speed Test" icon="fa-solid fa-tachometer-alt" />
                                </x-tyro-dashboard::sidebar-group>
                            </x-tyro-dashboard::sidebar>
                        </div>

                        {{-- Green Sidebar --}}
                        <div class="bg-muted p-4 rounded-xl border flex justify-center h-[500px]">
                            <x-tyro-dashboard::sidebar bg="#059669" color="white" class="rounded-lg shadow-lg">
                                <x-slot:header>
                                    <div class="flex items-center gap-2 px-2">
                                        <i class="fa-solid fa-leaf text-green-200"></i>
                                        <span class="font-bold">EcoSync</span>
                                    </div>
                                </x-slot:header>
                                <x-tyro-dashboard::sidebar-group heading="Environment">
                                    <x-tyro-dashboard::sidebar-item label="Solar Stats" icon="fa-solid fa-sun" active />
                                    <x-tyro-dashboard::sidebar-item label="Reports" icon="fa-solid fa-leaf" />
                                </x-tyro-dashboard::sidebar-group>
                            </x-tyro-dashboard::sidebar>
                        </div>

                        {{-- Red Sidebar --}}
                        <div class="bg-muted p-4 rounded-xl border flex justify-center h-[500px]">
                            <x-tyro-dashboard::sidebar bg="#dc2626" color="white" class="rounded-lg shadow-lg">
                                <x-slot:header>
                                    <div class="flex items-center gap-2 px-2">
                                        <i class="fa-solid fa-shield-heart text-white"></i>
                                        <span class="font-bold">Crimson</span>
                                    </div>
                                </x-slot:header>
                                <x-tyro-dashboard::sidebar-group heading="Security">
                                    <x-tyro-dashboard::sidebar-item label="Firewall" icon="fa-solid fa-fire" active />
                                    <x-tyro-dashboard::sidebar-item label="Threats" icon="fa-solid fa-biohazard" badge="4" badgeColor="warning" />
                                </x-tyro-dashboard::sidebar-group>
                            </x-tyro-dashboard::sidebar>
                        </div>

                        {{-- Blue Sidebar --}}
                        <div class="bg-muted p-4 rounded-xl border flex justify-center h-[500px]">
                            <x-tyro-dashboard::sidebar bg="#2563eb" color="white" class="rounded-lg shadow-lg">
                                <x-slot:header>
                                    <div class="flex items-center gap-2 px-2">
                                        <i class="fa-solid fa-anchor text-white"></i>
                                        <span class="font-bold">Ocean</span>
                                    </div>
                                </x-slot:header>
                                <x-tyro-dashboard::sidebar-group heading="Navigation">
                                    <x-tyro-dashboard::sidebar-item label="Vessels" icon="fa-solid fa-ship" active />
                                    <x-tyro-dashboard::sidebar-item label="Routes" icon="fa-solid fa-compass" />
                                </x-tyro-dashboard::sidebar-group>
                            </x-tyro-dashboard::sidebar>
                        </div>

                        {{-- Yellow/Amber Sidebar --}}
                        <div class="bg-muted p-4 rounded-xl border flex justify-center h-[500px]">
                            <x-tyro-dashboard::sidebar bg="#d97706" color="white" class="rounded-lg shadow-lg">
                                <x-slot:header>
                                    <div class="flex items-center gap-2 px-2">
                                        <i class="fa-solid fa-crown text-white"></i>
                                        <span class="font-bold">Amber</span>
                                    </div>
                                </x-slot:header>
                                <x-tyro-dashboard::sidebar-group heading="Incentives">
                                    <x-tyro-dashboard::sidebar-item label="Rewards" icon="fa-solid fa-gift" active />
                                    <x-tyro-dashboard::sidebar-item label="Quests" icon="fa-solid fa-map" />
                                </x-tyro-dashboard::sidebar-group>
                            </x-tyro-dashboard::sidebar>
                        </div>

                        {{-- Dark Slate Sidebar --}}
                        <div class="bg-muted p-4 rounded-xl border flex justify-center h-[500px]">
                            <x-tyro-dashboard::sidebar bg="#334155" color="white" class="rounded-lg shadow-lg">
                                <x-slot:header>
                                    <div class="flex items-center gap-2 px-2">
                                        <i class="fa-solid fa-terminal text-green-400"></i>
                                        <span class="font-bold">DevStack</span>
                                    </div>
                                </x-slot:header>
                                <x-tyro-dashboard::sidebar-group heading="System">
                                    <x-tyro-dashboard::sidebar-item label="Console" icon="fa-solid fa-code" active />
                                    <x-tyro-dashboard::sidebar-item label="Logs" icon="fa-solid fa-list" />
                                </x-tyro-dashboard::sidebar-group>
                            </x-tyro-dashboard::sidebar>
                        </div>
                    </div>
            </div>
        </section>
    </div>
</div>

<!-- Load component scripts BEFORE Alpine.js -->
<x-tyro-dashboard::component-scripts />

<!-- Alpine.js Plugins -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>

<!-- Alpine.js CDN -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@endsection

@push('scripts')
<!-- Font Awesome (for icons) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush
<script setup>
import { Head, Link } from "@inertiajs/vue3";

defineProps({ canLogin: Boolean, canRegister: Boolean });

const heroImage =
    "https://lh3.googleusercontent.com/aida-public/AB6AXuCVuveOHkUTO6dYrJpY3PeoOSvE3g3NQFwJ2CB3wYbN0PkUUersZT-zt7JvhfnnIbRbtTcNfOa3BQVzRALbutxY9ulzuI7a9-6b21_TST3rexKCb-ag1iAd-knfn3-mDux4LDMKxGpU9B-cAq9DdVNTeA5YuzD7Da_JGfwNEIEwALixudE8eS3g219VFSZdKkhtXujFOUldCYBZAcaU9DlYlYHxuALsK3UI7rrSoeWC5WyWp7QQB-E";
const overviewImage =
    "https://lh3.googleusercontent.com/aida-public/AB6AXuBiPkMqWDQj_xXCGu0-TeMDCJuYXDWiV_y0Bb7dPE-O6rXSsOYV1GpVSJ7ra7ykuEGaJLARYuCAYUHxqKzfLYIGMO9l5ASQNYYsunHzp5POm9cWjFYPEa6I0AGRDkigABlU6lyYwxyPCEkmPuvuHZg024Ko0xXWfHxMQaEKmfiMrvjquw-XnuindiTE82dOHNet_Q4mh6bKP-BW3LBAGMxUa5nZ9S7nclF74VLQaKItDGJsO4BjT4Q";
const overviewFallbackImage =
    "https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=1200&q=90";

const useOverviewFallback = (event) => {
    const image = event.currentTarget;
    if (image.src !== overviewFallbackImage) image.src = overviewFallbackImage;
};
const rides = [
    [
        "Water Ride",
        "Giant Slide",
        "Seluncuran raksasa memacu adrenalin untuk keluarga.",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuCJm1EyVij1UZEMqiDCLXoTwNdFpuhXET_OtO36MpekSzWf4iTc1OE7wbjeF8xaJg7e0f96aLHiRiarMfci9PxqjB6Vb94a_bgkN1NNnxS_Q11m09zoF4Ueow9ZLaNNrUMJ_ypCmJt8nNkb022njzxzfA4U8nFSXQwHEMBdbjPMV8TzIkzu-E2Bq5BooDkNLEhzem0KY_SDiAC9DQZ0S6PCsmfQcAaoYpfkcmizM80axCW4GxjKAxk",
        "text-secondary-container",
    ],
    [
        "Family Ride",
        "Kereta Radja",
        "Berkeliling taman dengan kereta mini yang menyenangkan.",
        "https:/ /lh3.googleusercontent.com/aida-public/AB6AXuDmcz23lVv4mb5Y21WesMtRwLlX4yzADaaE7LIt-ZH1VRh97Ywz1uiEMc6niEWZTszFFNSx0AHB_xGU5pHj9EnPhUGdfL3Bzp7I6lN7UYnbcuFpwv4HxmBEEiHzlGbnQ6G0Z9psqrbi0R1qVHuuQt591klBBbUhyOW-b_VautuBFUgNp0tqV1_hmuZPhNrp5eJ_F8UXD0zmaHZ7WmeKhIxT-yd0WfUxSU7Md2xs48be9h2vWGtb7i8",
        "text-primary",
    ],
    [
        "Thrill Ride",
        "Kora Kora",
        "Ayun setinggi langit di kapal bajak laut.",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuDytFh8so8GLFuo3NUXnJzB9Zcut9MpvPYg-2cylPibEeb4yN0T-0J1xkkKsX5Gac4QgXU0qWF88Uc034ko6SQxIf05HKpQ5WT2Ikefq2cruatsWb7EZ72QPZ9pyjWkkBTQQZP3uMrihXUvx3fiPR1SHLUc7BMHfwOYHVaOmQGBFQknl7-QKol4tNa2weCF2art0aPaPNEu22cCJ9o5mVEyFyy7fQxspJB9pbegBVpVqkOXcmwnbKY",
        "text-tertiary",
    ],
    [
        "Kids Ride",
        "Istana Boneka",
        "Jelajahi dunia penuh warna dan keajaiban.",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuDzfM-YOYagLvn3_znKFhEBS83LKGZJl8-wjLkYOqiAJjw4pQFptTyx5pQdwPrHhv6H6UM1de6zY8EtMjS7EoommGnuzySzJ6-hBhpyVzc1S7OGIl-jVXi0eyIlCvGh-qT1pb7oG6Q642iJB_fVZyRwZJIS6wnUPXCJWldXuOOGGu86AUJdBrQ_cIcHboVSaEPMlUbh9A9QIG1ouABbUi2DGHc_qzy797WhFN8J_8W49Cc228pwgUE",
        "text-[#166534]",
    ],
];
const news = [
    [
        "Event",
        "Festival Budaya Nusantara 2024",
        "Saksikan pertunjukan tari tradisional dan bazaar kuliner khas daerah akhir pekan ini.",
        "12 Okt 2024",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuBvASMVlUEahE2HXrSms6BjqKl6k61AcA3yL_D4Pdvdjp-OL8gOhyMdLabQyFmW7EpRXZuV8GFv_J9wke0xqXyWxowADs3jvqA5DiV-WKxD3l5kzWQQZ5FtcbXLBzJw2J75QEcFGJlujdp6VT54wjJ7Tyl4C-Hw_K7k1Lga0-tKGgpZPs3pCHcmUAAs6Eo_Oog0do_WzGJ5mWDcetA41-sHMf_HYU9huL6cFc5zqU-P0xz_UYOo1SU",
        "bg-primary",
    ],
    [
        "Berita",
        "Soft Opening Wahana Roller Coaster",
        "Nikmati sensasi memacu adrenalin dengan wahana terbaru kami mulai bulan depan.",
        "05 Okt 2024",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuBXAozKwdSfiXhbz3kHcBqtkX1ObY1lg9bnPVl6yQgcOZGqyDj6OhbCDbzumh1cHodnK5QR9_7TfCpz1WLVONR8XSZ0avJyt5FzRTjqZ0Wz4fjb5YI-Si_FJH8NHGPAnoWynBbBUgy-3DhEuw05TSAPhHVP5AgUfux1p_u0KKhBpshbyAnOvp0mrN5FcbfgTvRTouAe49WeLDJMTZykxFv_jEudRtWm7CNstpQAS-wVHV3cGQJP_us",
        "bg-tertiary",
    ],
    [
        "Info",
        "Penyesuaian Jam Operasional Libur Nasional",
        "Informasi penting mengenai perubahan jam buka selama periode libur panjang.",
        "28 Sep 2024",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuBBXp3AiZNkTsYwiXhDw953lEoFkrMuiIVhUQR70QVV69HasjhkzkH6Qd-uOhlDdPlrH_D-ec5Atnjg-CyEBbLFj8cxMpt4heuu8LM0xsITPC9bHlfk5WQtpCAMwIjPioj1wxGfez8DloNjRr0jdH-eccDMhN9sXJdueJC9thfEBc7tL02IcrtTmBywVQZQUCF9LQX_z88w2kCP8wOknFVkD1vPiOQPwXHO7EpiracIzh9DZRu_Qxk",
        "bg-[#ca8a04]",
    ],
    [
        "Event",
        "Family Fun Run 5K Kampoeng Radja",
        "Ikuti lomba lari santai mengelilingi area taman yang asri bersama keluarga.",
        "15 Sep 2024",
        "https://lh3.googleusercontent.com/aida-public/AB6AXuDsq6NxqzzQ1m7hgVl2pOF1TTodlX1ZJsTtUOo_YQPKj8HcX81VAzthHWi5dW8EOag2iCH4MroVWzI_NbLZy3-W2U78OBH0nbq9oSyowaSTooeZMc5JhSR84VM7VETzP-Blw7X4IdtcTvNwcbmnVF0gPRNvT5wylrHlj8AoZFsufM4ypritcnYzEGVEXR8q5Y-jZpecEEAcEaOyGy4hbeqyI4RsLQQZtO0E6n-UFMhbt_g5RLarj7g",
        "bg-primary",
    ],
];
const partners = [
    "Bank BRI",
    "Telkomsel",
    "Indofood",
    "Aqua",
    "Coca-Cola",
    "JNE",
    "Pertamina",
];
</script>

<template>
    <Head title="Wisata Kampoeng Radja" />
    <div class="bg-background font-body-md text-on-surface antialiased">
        <nav
            class="sticky top-0 z-50 w-full bg-surface/90 shadow-sm backdrop-blur-md"
        >
            <div
                class="mx-auto flex max-w-container-max items-center justify-between px-6 py-4 lg:px-margin-lg"
            >
                <a
                    href="#beranda"
                    class="font-headline-sm text-headline-sm font-extrabold text-primary"
                    >Wisata Kampoeng Radja</a
                >
                <div class="hidden items-center gap-8 md:flex">
                    <a
                        href="#beranda"
                        class="border-b-2 border-primary pb-1 font-bold text-primary"
                        >Beranda</a
                    >
                    <a
                        href="#tentang"
                        class="text-on-surface-variant transition-colors hover:text-primary"
                        >Tentang Kami</a
                    >
                    <a
                        href="#galeri"
                        class="text-on-surface-variant transition-colors hover:text-primary"
                        >Galeri</a
                    >
                    <Link
                        :href="route('berita')"
                        class="text-on-surface-variant transition-colors hover:text-primary"
                        >Media &amp; Berita</Link
                    >
                    <a
                        href="#kontak"
                        class="text-on-surface-variant transition-colors hover:text-primary"
                        >Kontak &amp; Lokasi</a
                    >
                </div>
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="flex items-center gap-2 rounded-full bg-secondary-container px-6 py-2 font-label-bold text-on-secondary-container"
                    >Dashboard
                    <span class="material-symbols-outlined text-[18px]"
                        >login</span
                    ></Link
                >
                <a
                    v-else
                    :href="canLogin ? route('login') : '#wahana'"
                    class="flex items-center gap-2 rounded-full bg-secondary-container px-6 py-2 font-label-bold text-on-secondary-container transition-transform active:scale-90"
                    >Login
                    <span class="material-symbols-outlined text-[18px]"
                        >login</span
                    ></a
                >
            </div>
        </nav>

        <main>
            <section
                id="beranda"
                class="relative flex aspect-video w-full items-center justify-center py-section-padding-desktop"
            >
                <div
                    class="absolute inset-0 bg-cover bg-center"
                    :style="{ backgroundImage: `url(${heroImage})` }"
                ></div>
                <div class="absolute inset-0 bg-black/40"></div>
                <div
                    class="relative z-10 mx-auto flex max-w-4xl flex-col items-center px-4 text-center"
                >
                    <span
                        class="mb-2 font-headline-sm text-secondary-fixed drop-shadow-md"
                        >Selamat Datang di</span
                    >
                    <h1
                        class="mb-6 font-display-lg text-4xl font-extrabold leading-tight text-on-primary drop-shadow-lg md:text-5xl lg:text-6xl"
                    >
                        Wisata Kampoeng Radja
                    </h1>
                    <div
                        class="mb-8 inline-block rounded-full bg-tertiary-container/90 px-6 py-2 shadow-lg backdrop-blur-sm"
                    >
                        <span
                            class="font-headline-sm text-on-tertiary-container"
                            >Kesenangan tiada akhir di kampoeng radja</span
                        >
                    </div>
                    <p
                        class="mb-10 max-w-2xl text-body-lg text-white drop-shadow-md"
                    >
                        Destinasi wisata keluarga dengan berbagai wahana seru,
                        budaya khas Nusantara, dan pengalaman bermain yang tak
                        terlupakan.
                    </p>
                    <div
                        class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                    >
                        <a
                            :href="canLogin ? route('login') : '#wahana'"
                            class="flex items-center gap-2 rounded-xl bg-secondary-container px-8 py-3 font-label-bold text-on-secondary-fixed shadow-lg transition-colors hover:bg-secondary-fixed-dim"
                            >Login
                            <span class="material-symbols-outlined icon-fill"
                                >login</span
                            ></a
                        >
                        <a
                            href="#wahana"
                            class="flex items-center gap-2 rounded-xl bg-surface/90 px-8 py-3 font-label-bold text-primary shadow-lg backdrop-blur-sm transition-colors hover:bg-surface"
                            >Jelajah Wahana
                            <span class="material-symbols-outlined"
                                >arrow_forward</span
                            ></a
                        >
                    </div>
                </div>
                <div
                    class="absolute -bottom-16 left-1/2 z-20 w-[90%] max-w-container-max -translate-x-1/2"
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-6 rounded-xl border border-outline-variant/30 bg-white p-6 shadow-lg md:flex-nowrap"
                    >
                        <div
                            class="flex flex-1 items-center justify-center gap-4 md:justify-start"
                        >
                            <span
                                class="grid h-12 w-12 place-items-center rounded-full bg-primary-fixed text-primary"
                                ><span
                                    class="material-symbols-outlined icon-fill"
                                    >attractions</span
                                ></span
                            >
                            <div>
                                <b
                                    class="font-headline-md text-headline-md leading-tight text-primary"
                                    >20+</b
                                >
                                <p class="text-caption text-on-surface-variant">
                                    Wahana Seru
                                </p>
                            </div>
                        </div>
                        <i
                            class="hidden h-12 w-px bg-outline-variant/50 md:block"
                        ></i>
                        <div
                            class="flex flex-1 items-center justify-center gap-4"
                        >
                            <span
                                class="grid h-12 w-12 place-items-center rounded-full bg-secondary-fixed text-secondary"
                                ><span
                                    class="material-symbols-outlined icon-fill"
                                    >groups</span
                                ></span
                            >
                            <div>
                                <b
                                    class="font-headline-md text-headline-md leading-tight text-primary"
                                    >100K+</b
                                >
                                <p class="text-caption text-on-surface-variant">
                                    Pengunjung/Tahun
                                </p>
                            </div>
                        </div>
                        <i
                            class="hidden h-12 w-px bg-outline-variant/50 md:block"
                        ></i>
                        <div
                            class="flex flex-1 items-center justify-center gap-4"
                        >
                            <span
                                class="grid h-12 w-12 place-items-center rounded-full bg-tertiary-fixed text-tertiary"
                                ><span
                                    class="material-symbols-outlined icon-fill"
                                    >stars</span
                                ></span
                            >
                            <div>
                                <b
                                    class="font-headline-md text-headline-md leading-tight text-primary"
                                    >20</b
                                >
                                <p class="text-caption text-on-surface-variant">
                                    Tahun Berpengalaman
                                </p>
                            </div>
                        </div>
                        <i
                            class="hidden h-12 w-px bg-outline-variant/50 md:block"
                        ></i>
                        <div
                            class="flex flex-1 items-center justify-center gap-4 md:justify-end"
                        >
                            <span
                                class="grid h-12 w-12 place-items-center rounded-full bg-[#dcfce7] text-[#166534]"
                                ><span
                                    class="material-symbols-outlined icon-fill"
                                    >family_restroom</span
                                ></span
                            >
                            <div>
                                <b
                                    class="font-headline-md text-headline-md leading-tight text-primary"
                                    >Keluarga</b
                                >
                                <p class="text-caption text-on-surface-variant">
                                    Ramah &amp; Edukatif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="tentang"
                class="mx-auto grid max-w-container-max items-center gap-16 overflow-hidden px-gutter pb-section-padding-desktop pt-[140px] lg:grid-cols-2"
            >
                <div>
                    <span
                        class="mb-2 block font-label-bold uppercase tracking-wider text-secondary-container"
                        >Tentang Kami</span
                    >
                    <h2
                        class="mb-6 font-headline-lg text-headline-lg text-primary"
                    >
                        Company Overview
                    </h2>
                    <p class="mb-4 text-body-md text-on-surface-variant">
                        Wisata Kampoeng Radja adalah destinasi wisata keluarga
                        yang menghadirkan perpaduan wahana modern, budaya
                        Nusantara, dan alam yang asri.
                    </p>
                    <p class="mb-8 text-body-md text-on-surface-variant">
                        Kami berkomitmen memberikan pengalaman berwisata yang
                        aman, nyaman, edukatif, dan penuh kesenangan untuk semua
                        usia.
                    </p>
                    <a
                        href="#wahana"
                        class="flex w-fit items-center gap-2 rounded-xl bg-primary px-6 py-2 font-label-bold text-on-primary"
                        >Selengkapnya
                        <span class="material-symbols-outlined text-[18px]"
                            >arrow_forward</span
                        ></a
                    >
                </div>
                <div class="relative">
                    <div
                        class="relative z-10 overflow-hidden rounded-xl border border-outline-variant/20 shadow-lg"
                    >
                        <img
                            :src="overviewImage"
                            alt="Tim Wisata Kampoeng Radja"
                            class="h-auto w-full object-cover"
                            @error="useOverviewFallback"
                        />
                    </div>
                    <div
                        class="absolute -bottom-8 -right-8 z-20 w-64 rotate-[-2deg] rounded-xl border border-outline-variant/10 bg-white p-6 text-center shadow-xl"
                    >
                        <span
                            class="material-symbols-outlined absolute right-4 top-4 text-4xl text-primary opacity-20"
                            >format_quote</span
                        >
                        <p class="font-headline-sm text-on-surface">
                            Kesenangan tiada akhir di kampoeng radja
                        </p>
                    </div>
                    <div
                        class="absolute -left-10 -top-10 z-0 text-secondary-fixed/50"
                    >
                        <svg width="100" height="100" viewBox="0 0 100 100">
                            <circle
                                cx="50"
                                cy="50"
                                r="40"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="4"
                                stroke-dasharray="8 8"
                            />
                        </svg>
                    </div>
                </div>
            </section>

            <section
                id="wahana"
                class="mx-auto max-w-container-max overflow-hidden px-gutter py-section-padding-desktop"
            >
                <div
                    class="rounded-3xl border border-outline-variant/20 bg-surface-container-low p-8 shadow-sm lg:p-12"
                >
                    <div class="grid items-center gap-12 lg:grid-cols-3">
                        <div>
                            <h2
                                class="mb-8 font-headline-lg text-headline-lg leading-tight text-primary"
                            >
                                Wahana &amp; Keunggulan
                            </h2>
                            <ul class="space-y-6">
                                <li
                                    v-for="feature in [
                                        [
                                            'attractions',
                                            'bg-primary-container text-on-primary-container',
                                            'Wahana Lengkap',
                                            'Beragam wahana untuk semua usia.',
                                        ],
                                        [
                                            'health_and_safety',
                                            'bg-secondary-container text-on-secondary-container',
                                            'Aman & Nyaman',
                                            'Standar keamanan terbaik untuk pengunjung.',
                                        ],
                                        [
                                            'school',
                                            'bg-tertiary-container text-on-tertiary-container',
                                            'Edukasi & Budaya',
                                            'Belajar sambil bermain dengan nuansa budaya.',
                                        ],
                                        [
                                            'family_restroom',
                                            'bg-[#dcfce7] text-[#166534]',
                                            'Ramah Keluarga',
                                            'Fasilitas memadai untuk kenyamanan keluarga.',
                                        ],
                                    ]"
                                    :key="feature[2]"
                                    class="flex items-start gap-4"
                                >
                                    <span
                                        :class="feature[1]"
                                        class="grid h-10 w-10 shrink-0 place-items-center rounded-full"
                                        ><span
                                            class="material-symbols-outlined"
                                            >{{ feature[0] }}</span
                                        ></span
                                    >
                                    <div>
                                        <h3
                                            class="mb-1 font-headline-sm text-headline-sm text-on-surface"
                                        >
                                            {{ feature[2] }}
                                        </h3>
                                        <p
                                            class="text-sm text-on-surface-variant"
                                        >
                                            {{ feature[3] }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div
                            id="galeri"
                            class="hide-scrollbar relative flex w-full snap-x snap-mandatory gap-6 overflow-x-auto pb-6 pt-2 lg:col-span-2"
                        >
                            <article
                                v-for="ride in rides"
                                :key="ride[1]"
                                class="flex w-[280px] shrink-0 snap-start flex-col overflow-hidden rounded-2xl border border-outline-variant/30 bg-white shadow-sm transition-shadow hover:shadow-md"
                            >
                                <img
                                    :src="ride[3]"
                                    :alt="ride[1]"
                                    class="h-[200px] w-full object-cover"
                                />
                                <div class="flex flex-1 flex-col p-5">
                                    <span
                                        :class="ride[4]"
                                        class="mb-2 text-xs font-label-bold uppercase tracking-wider"
                                        >{{ ride[0] }}</span
                                    >
                                    <h3
                                        class="mb-2 font-headline-sm text-headline-sm text-on-surface"
                                    >
                                        {{ ride[1] }}
                                    </h3>
                                    <p class="text-sm text-on-surface-variant">
                                        {{ ride[2] }}
                                    </p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="overflow-hidden border-y border-outline-variant/20 bg-surface-container-highest py-section-padding-desktop"
            >
                <div
                    class="mx-auto mb-8 max-w-container-max px-gutter text-center"
                >
                    <h2
                        class="font-headline-md text-headline-md text-on-surface"
                    >
                        Bersama Membangun Kesenangan
                    </h2>
                </div>
                <div
                    class="flex w-max items-center gap-16 whitespace-nowrap px-8 animate-marquee"
                >
                    <template v-for="loop in 3" :key="loop"
                        ><span
                            v-for="partner in partners"
                            :key="`${loop}-${partner}`"
                            class="font-headline-sm text-headline-sm font-bold uppercase tracking-widest text-on-surface-variant/50"
                            >{{ partner }}</span
                        ></template
                    >
                </div>
            </section>

            <section
                id="berita"
                class="mx-auto max-w-container-max px-gutter py-section-padding-desktop"
            >
                <div class="mb-12 flex items-end justify-between">
                    <div>
                        <span
                            class="mb-2 block font-label-bold uppercase tracking-wider text-secondary-container"
                            >Updates</span
                        >
                        <h2
                            class="font-headline-lg text-headline-lg text-primary"
                        >
                            Berita &amp; Event Terbaru
                        </h2>
                    </div>
                    <a
                        href="#kontak"
                        class="hidden items-center gap-2 rounded-xl border border-outline-variant/50 bg-white px-6 py-2 font-label-bold text-primary md:flex"
                        >Lihat Semua
                        <span class="material-symbols-outlined text-[18px]"
                            >arrow_forward</span
                        ></a
                    >
                </div>
                <div
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4"
                >
                    <a
                        v-for="item in news"
                        :key="item[1]"
                        href="#"
                        class="group flex flex-col overflow-hidden rounded-2xl border border-outline-variant/30 bg-white transition-all hover:shadow-lg"
                        ><div class="relative h-48 overflow-hidden">
                            <img
                                :src="item[4]"
                                :alt="item[1]"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            /><span
                                :class="item[5]"
                                class="absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-bold text-white"
                                >{{ item[0] }}</span
                            >
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <h3
                                class="mb-2 font-headline-sm text-headline-sm text-on-surface transition-colors group-hover:text-primary"
                            >
                                {{ item[1] }}
                            </h3>
                            <p
                                class="mb-4 flex-1 text-sm text-on-surface-variant"
                            >
                                {{ item[2] }}
                            </p>
                            <span
                                class="text-xs font-label-bold text-outline"
                                >{{ item[3] }}</span
                            >
                        </div></a
                    >
                </div>
                <div class="mt-8 flex justify-center md:hidden">
                    <a
                        href="#kontak"
                        class="flex items-center gap-2 rounded-xl border border-outline-variant/50 bg-white px-6 py-2 font-label-bold text-primary"
                        >Lihat Semua
                        <span class="material-symbols-outlined text-[18px]"
                            >arrow_forward</span
                        ></a
                    >
                </div>
            </section>
        </main>

        <footer
            id="kontak"
            class="mt-section-padding-desktop w-full bg-primary pb-4 pt-margin-lg"
        >
            <div
                class="mx-auto mb-8 grid max-w-container-max grid-cols-1 gap-gutter px-gutter md:grid-cols-4"
            >
                <div>
                    <span
                        class="mb-4 block font-headline-md text-headline-md font-bold text-on-primary"
                        >Wisata Kampoeng Radja</span
                    >
                    <p class="mb-6 text-body-md text-on-primary/80">
                        Destinasi wisata keluarga dengan berbagai wahana seru,
                        budaya khas Nusantara, dan pengalaman tak terlupakan.
                    </p>
                    <div class="flex gap-4">
                        <span class="material-symbols-outlined text-on-primary"
                            >qr_code_2</span
                        ><span class="material-symbols-outlined text-on-primary"
                            >camera_alt</span
                        >
                    </div>
                </div>
                <div>
                    <h3 class="mb-4 font-label-bold text-on-primary">Menu</h3>
                    <div class="space-y-2 text-on-primary/80">
                        <a class="block" href="#beranda">Beranda</a
                        ><a class="block" href="#tentang">Tentang Kami</a
                        ><a class="block" href="#galeri">Galeri</a
                        ><a class="block" href="#berita">Media &amp; Berita</a
                        ><a class="block" href="#kontak">Kontak &amp; Lokasi</a>
                    </div>
                </div>
                <div>
                    <h3 class="mb-4 font-label-bold text-on-primary">
                        Informasi
                    </h3>
                    <div class="space-y-2 text-on-primary/80">
                        <a class="block" href="#wahana">Login</a
                        ><a class="block" href="#kontak">Jam Operasional</a
                        ><a class="block" href="#wahana">Fasilitas</a
                        ><a class="block" href="#kontak">FAQ</a>
                    </div>
                </div>
                <div>
                    <h3 class="mb-4 font-label-bold text-on-primary">
                        Kontak Kami
                    </h3>
                    <div class="space-y-4 text-on-primary/80">
                        <p class="flex gap-3">
                            <span class="material-symbols-outlined"
                                >location_on</span
                            >Jl. Kampoeng Radja No. 1, Desa Wisata, Kec.
                            Bahagia, Kab. Sejahtera 12345
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="material-symbols-outlined">phone</span
                            >(021) 1234 5678
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="material-symbols-outlined">mail</span
                            >info@kampoengradja.com
                        </p>
                    </div>
                </div>
            </div>
            <div
                class="mx-auto flex max-w-container-max flex-col items-center justify-between gap-4 border-t border-on-primary/20 px-gutter pt-6 text-on-primary/80 md:flex-row"
            >
                <p>© 2024 Wisata Kampoeng Radja. All Rights Reserved.</p>
                <div class="flex gap-4">
                    <a href="#">Kebijakan Privasi</a
                    ><span class="text-on-primary/40">|</span
                    ><a href="#">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </footer>
    </div>
</template>

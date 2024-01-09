<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    fill="none"
    :stroke="stroke"
    viewBox="0 0 21 21"
  >
    <g clip-path="url(#clip0_2746_3617)">
      <path
        d="M13.8335 13.8332L10.5002 10.4999M10.5002 10.4999L7.16688 13.8332M10.5002 10.4999V17.9999M17.4919 15.8249C18.3047 15.3818 18.9467 14.6806 19.3168 13.8321C19.6868 12.9835 19.7637 12.0359 19.5354 11.1388C19.307 10.2417 18.7865 9.44616 18.0558 8.87778C17.3251 8.30939 16.4259 8.00052 15.5002 7.9999H14.4502C14.198 7.02427 13.7278 6.11852 13.0752 5.35073C12.4225 4.58295 11.6042 3.97311 10.682 3.56708C9.75967 3.16104 8.75734 2.96937 7.75031 3.00647C6.74328 3.04358 5.75777 3.30849 4.86786 3.78129C3.97795 4.2541 3.2068 4.92249 2.6124 5.73622C2.01799 6.54996 1.61579 7.48785 1.43603 8.4794C1.25627 9.47095 1.30363 10.4903 1.57456 11.461C1.84548 12.4316 2.33291 13.3281 3.00021 14.0832"
        stroke=""
        stroke-width="1.67"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </g>
    <defs>
      <clipPath id="clip0_2746_3617">
        <rect
          width="20"
          height="20"
          :fill="fill"
          transform="translate(0.5 0.5)"
        />
      </clipPath>
    </defs>
  </svg>
</template>

<script setup>
  const props = defineProps({
    width: { type: String, default: '100%' },
    height: { type: String, default: '100%' },
    fill: { type: String, default: 'white' },
    stroke: { type: String, default: 'white' },
  });
</script>

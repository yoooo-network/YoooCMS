# Embeddable profile widget

The widget is a dependency-free script at `public/widget.js`. Deploy that file on the HTTPS host used for the embed URL (for example, `https://cdn.yooo.app/widget.js`). It discovers its own `<script>` tag, reads that tag's `data-*` options, and inserts a Shadow DOM widget immediately after it. Multiple tags on one page are supported.

```html
<script
  src="https://cdn.yooo.app/widget.js"
  data-city="Mumbai"
  data-country="India"
  data-gender="female"
  data-verified="true"
  data-limit="12"
  data-layout="grid"
  data-theme="light"
  data-heading="Profiles in Mumbai"
  data-primary-color="#6d4aff"
  data-show-location="true"
  data-show-rating="true">
</script>
```

## Options

| Attribute | Values | Default |
| --- | --- | --- |
| `data-city` | City name | No city filter |
| `data-country` | Country name | No country filter |
| `data-gender` | `female`, `male`, or `trans` | `female` |
| `data-verified` | `true` or `false` | `false` |
| `data-limit` | Integer from 1 to 50 | `12` |
| `data-layout` | `grid`, `list`, or `carousel` | `grid` |
| `data-theme` | `light` or `dark` | `light` |
| `data-primary-color` | 3 or 6 digit hex color | `#6d4aff` |
| `data-heading` | Plain text, up to 100 characters | `Featured profiles` |
| `data-show-location` | `true` or `false` | `true` |
| `data-show-rating` | `true` or `false` | `true` |

The grid uses one column on small phones, two columns on tablet sized screens, and three columns on desktop. Carousel cards can be scrolled horizontally and have previous/next controls. If rating fields are present in the API response, they are shown; the current profiles endpoint does not provide rating data, so no rating is displayed today.

## Data and deployment

The script requests `https://api.yooo.app/api/v1/profiles` with the gender, location, verification, and limit filters. Images, descriptions, names, and locations are treated as text/data and assigned through DOM properties; API HTML is never inserted. Only HTTPS image/profile URLs are used. Requests omit credentials and the browser contains no API secret.

The public profiles route allows cross-origin GET requests for embedding and returns cacheable listing data from the existing API model cache. Verify that the CDN serves `widget.js` with JavaScript content type and HTTPS enabled, and that the API host serves the updated application with CORS enabled before publishing the snippet.

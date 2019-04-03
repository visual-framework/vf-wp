# Breadcrumbs (container)

Display the `vf-breadcrumbs` Visual Framework pattern. The template includes a placeholder that is appended to via JavaScript rather than using a server-side API request.

## Global Configuration

| option_name | option_value |
| ----------- | ------------ |
| embl_taxonomy_term_who | [TERM_ID] |
| embl_taxonomy_term_what | [TERM_ID] |
| embl_taxonomy_term_where | [TERM_ID] |

Options provided by the **EMBL Taxonomy** plugin.

These three options are used by the VF JavaScript to render the breadcrumbs pattern. The EMBL Taxonomy plugin outputs `<meta>` tags in the document.

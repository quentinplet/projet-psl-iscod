function getImageUrl(imagePath) {
  return imagePath
    ? `${import.meta.env.VITE_API_BASE_URL}/${imagePath}`
    : "/images/no-image.png";
}

function toFormData(obj) {
  const formData = new FormData();
  for (const [key, value] of Object.entries(obj)) {
    if (value !== undefined && value !== null) {
      formData.append(key, value);
    }
  }
  return formData;
}

export { getImageUrl, toFormData };

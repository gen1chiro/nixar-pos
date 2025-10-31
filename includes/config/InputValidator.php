<?php 
    class InputValidator {
        /**
         * Trim and escape special HTML characters in a string.
         * @param mixed $Data
         * @return string
         */
        public static function sanitizeData($Data) {
            return htmlspecialchars(trim($Data));
        }

        public static function sanitizeArray($DataArray)  {
            $Sanitized = [];

            foreach($DataArray as $Key => $Value) {
                $Sanitized[$Key] = is_array($Value) ? 
                    self::sanitizeArray($Value) : InputValidator::sanitizeData($Value);
            }

            return $Sanitized;
        }
    }

    class Image {
        public static function uploadToDirectory(array $Image) {
            $DEFAULT_IMG = 'default-product.png';
            // No image detected
            if (empty($Image) || $Image['error'] !== 0) {
                return $DEFAULT_IMG;
            }

            $Dir = __DIR__ . '/../../public/assets/img/uploads/';
            $ImgPath = basename($Image['name']);
            $FileName = time(). '_' . uniqid() . '_' . $ImgPath;
            $SavePath = "{$Dir}{$FileName}";
            // Save image to `public/img/uploads`
            if(move_uploaded_file($Image['tmp_name'], $SavePath)) {
                return $FileName;
            }
            return $DEFAULT_IMG;
        }
    }
?>
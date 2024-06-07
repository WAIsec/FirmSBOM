import os
import magic

WEB_EXTENSION = ['.html', '.htm', '.xhtml', '.xml', '.css', '.scss', '.sass', '.less', '.js', '.ts', '.jsx', 'tsx', 'php', '.asp', '.aspx', '.jsp']

class LevelOneAnalyzer:
    def __init__(self, fs_path):
        self.fs_path = fs_path
        self.web_files = []
        self.os_bins = []
        self.ven_bins = []
        self.conf_files = []

    def analyze(self):
        try:
            self.find_web_files()
            self.find_os_binary()
            self.find_vendor_files()
            self.find_configuration_files()
            return 0
        except Exception as e:
            print(f"Error during analysis: {e}")
            return 1

    def find_web_files(self):
        """
        This function finds web source
        <param>
        [fs_path]: filesystem path extracted from firmware file
        <return>
        [websource_list]: the list of websource existed in firmware filesystem
        """
        for dirpath, _, filenames in os.walk(self.fs_path):
            for filename in filenames:
                if any(filename.lower().endswith(ext) for ext in WEB_EXTENSION):
                    self.web_files.append(os.path.join(dirpath, filename))

    def find_os_binary(self):
        """
        This function finds binary file generated by OS
        <param>
        [fs_path]: filesystem path extracted from firmware file
        <return>
        [os_bin_list]: the list of binaries generated by OS in firmware filesystem
        """
        path_list = ['/bin', '/sbin', '/usr/bin', '/usr/sbin']
        mime = magic.Magic(mime=True)

        for sub_path in path_list:
            full_path = self.fs_path + sub_path
            if os.path.isdir(full_path):
                for dirpath, _, filenames in os.walk(full_path):
                    for filename in filenames:
                        file_path = os.path.join(dirpath, filename)
                        if os.path.isfile(file_path):
                            mime_type = mime.from_file(file_path)
                            if mime_type in ['application/x-executable', 'application/x-sharedlib']:
                                self.os_bins.append(file_path)

    def find_vendor_files(self):
        """
        This function finds binary or normal file generated by Manufacturer
        <param>
        [fs_path]: filesystem path extracted from firmware file
        <return>
        [ven_bins]: the list of files generated by vendor
        """
        path_list = ['/usr/local', '/opt']
        mime = magic.Magic(mime=True)

        for sub_path in path_list:
            full_path = self.fs_path + sub_path
            if os.path.isdir(full_path):
                for dirpath, _, filenames in os.walk(full_path):
                    for filename in filenames:
                        file_path = os.path.join(dirpath, filename)
                        if os.path.isfile(file_path):
                            mime_type = mime.from_file(file_path)
                            if mime_type in ['application/x-executable', 'application/x-sharedlib']:
                                self.ven_bins.append(file_path)
            else:
                continue

    def find_configuration_files(self):
        """
        This function finds binary or normal file generated by Manufacturer
        <param>
        [fs_path]: filesystem path extracted from firmware file
        <return>
        [conf_files]: the list of configuration files generated by vendor
        """
        if os.path.isdir(self.fs_path):
            for dirpath, _, filenames in os.walk(self.fs_path):
                for filename in filenames:
                    file_path = os.path.join(dirpath, filename)
                    if os.path.isfile(file_path):
                        if filename.endswith('.conf'):
                            self.conf_files.append(os.path.join(dirpath, filename))

    def get_web_files(self):
        return self.web_files

    def get_os_bins(self):
        return self.os_bins

    def get_vendor_bins(self):
        return self.ven_bins
    
    def get_configuration_files(self):
        return self.conf_files